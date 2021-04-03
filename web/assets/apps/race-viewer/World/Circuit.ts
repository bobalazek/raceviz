import * as THREE from 'three';
import { Vector3 } from 'three';

import Application from '../Application';
import store from '../GUI/store';
import {
  setPositions,
} from '../GUI/store/appSlice';
export default class Circuit {
  public playbackMultiplier: number = 1;

  private curvePath: THREE.CurvePath<THREE.Vector3>;
  private size: number = 8192;

  constructor() {
    this._prepareLights();
    this._prepareGround();
    this._prepareLap();
    this._prepareVehicles();
    this._prepareGuiSync();
  }

  private _prepareLights() {
    const hemisphereLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 3);
    hemisphereLight.position.set(0, 50, 0);

    Application.scene.add(hemisphereLight);
  }

  private _prepareGround() {
    const groundGeometry = new THREE.PlaneGeometry(this.size, this.size);
    const groundMaterial = new THREE.MeshPhongMaterial({ color: 0xb3b3b3 });

    const ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.name = 'ground';
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;
    ground.matrixAutoUpdate = false;
    ground.updateMatrix();

    Application.scene.add(ground);
  }

  private _prepareLap() {
    this.curvePath = this._getCurvePath();
    const points = this.curvePath.getPoints(64);

    const geometry = new THREE.BufferGeometry().setFromPoints(points);
    const material = new THREE.LineBasicMaterial({ color : 0xff0000 });

    const curveObject = new THREE.Line(geometry, material);
    curveObject.position.y = 0.1;

    Application.scene.add(curveObject);
  }

  private _prepareVehicles() {
    const curvePath = this._getCurvePath();
    const up = new THREE.Vector3(0, 0, 1);
    const axis = new THREE.Vector3();

    const vehicles = [];
    for (const key in Application.world.resourceManager.driverVehicles) {
      const vehicleObject = Application.world.resourceManager.driverVehicles[key];

      vehicleObject.traverse((child: THREE.Object3D) => {
        child.castShadow = true;
        child.receiveShadow = true;
      });

      vehicleObject.userData.lap = 0;
      vehicleObject.userData.lapLocation = 0;
      vehicleObject.userData.position = 0;
      vehicleObject.userData.retired = false;
      vehicleObject.userData.finished = false;

      vehicleObject.position.copy(curvePath.getPoint(0));

      Application.scene.add(vehicleObject);

      vehicles.push(vehicleObject);
    }

    const followTargetVehicle = Application.scene.getObjectByName('vehicles_HAM');

    Application.world.camera.setFollowTarget(followTargetVehicle);

    // Prepare vehicle location total map
    let vehiclesLapLocationTotal = {};
    vehicles.map((vehicle) => {
      const key = vehicle.userData.raceDriver.id;
      vehiclesLapLocationTotal[key] = 1;
    });

    const raceLaps = Application.parameters.race.laps;
    const retirementPositionVector = new Vector3(10, 0, 0);
    Application.emitter.on('tick', (delta) => {
      for (var i = 0; i < vehicles.length; i++) {
        const vehicle = <THREE.Object3D>vehicles[i];
        const key = vehicle.userData.raceDriver.id;
        const lap = parseInt(vehiclesLapLocationTotal[key]);
        const lapLocation = vehiclesLapLocationTotal[key] % 1;
        const lapData = vehicle.userData.laps[lap];
        const lapTime = lapData?.time;
        const retired = vehicle.userData.retired;
        const finished = vehicle.userData.finished;

        if (
          retired ||
          !lapTime
        ) {
          continue;
        }

        if (
          !retired &&
          !lapTime &&
          lap < raceLaps
        ) {
          vehicle.userData.retired = true;
          vehicle.position.add(retirementPositionVector);

          continue;
        }

        const positionNew = curvePath.getPoint(lapLocation);
        const tangent = curvePath.getTangent(lapLocation);
        const radians = Math.acos(up.dot(tangent));
        const speed = (1 / lapTime) * 10000 * this.playbackMultiplier;

        axis.crossVectors(up, tangent).normalize();

        vehicle.position.copy(positionNew);
        vehicle.quaternion.setFromAxisAngle(axis, radians);

        vehiclesLapLocationTotal[key] += delta * speed;
        if (vehiclesLapLocationTotal[key] < 1) {
          vehiclesLapLocationTotal[key] = 1;
        } else if (vehiclesLapLocationTotal[key] > raceLaps) {
          vehiclesLapLocationTotal[key] = raceLaps + 0.99999999;

          vehicle.userData.finished = true;
        }

        vehicle.userData.lap = lap;
        vehicle.userData.lapLocation = lapLocation;
        vehicle.userData.position = lapData.position;
      }
    });
  }

  private _prepareGuiSync() {
    const vehicles = [];
    Application.scene.traverse((child: THREE.Object3D) => {
      if (child.name.startsWith('vehicles_')) {
        vehicles.push(child);
      }
    });

    setInterval(() => {
      this._syncPositionsToGui(vehicles);
    }, 1000);
  }

  private _syncPositionsToGui(vehicles: THREE.Object3D[]) {
    const positions = [];
    for (var i = 0; i < vehicles.length; i++) {
      const vehicle = <THREE.Object3D>vehicles[i];
      const lap = vehicle.userData.lap;
      const position = vehicle.userData.position;
      const code = vehicle.userData.raceDriver.season_driver.code;
      const retired = vehicle.userData.retired;
      const finished = vehicle.userData.finished;

      positions.push({
        code,
        lap,
        position,
        retired,
        finished,
      });
    }

    positions.sort((a, b) => {
      if (a.position === 0) {
        return 1;
      }
      if (b.position === 0) {
        return -1;
      }

      return a.position - b.position;
    });

    store.dispatch(setPositions(positions));
  }

  private _getSegments() {
    const segments: Array<THREE.Curve<THREE.Vector3>> = [];

    const anchorPoints = [
      new THREE.Vector3(-100, 0, 20),
      new THREE.Vector3(100, 0, 20),
    ];

    segments.push(
      new THREE.CubicBezierCurve3(
        anchorPoints[0],
        anchorPoints[0].clone().multiply(new Vector3(1, 1, 5)),
        anchorPoints[1].clone().multiply(new Vector3(1, 1, 5)),
        anchorPoints[1]
      )
    );

    segments.push(
      new THREE.CubicBezierCurve3(
        anchorPoints[1],
        anchorPoints[1].clone().multiply(new Vector3(1, 1, -5)),
        anchorPoints[0].clone().multiply(new Vector3(1, 1, -5)),
        anchorPoints[0]
      )
    );

    return segments;
  }

  private _getCurvePath() {
    const curvePath = new THREE.CurvePath<THREE.Vector3>();

    const segments = this._getSegments();
    for (let i = 0; i < segments.length; i++) {
      curvePath.add(segments[i]);
    }

    return curvePath;
  }
}
