import * as THREE from 'three';
import { CurvePath, Vector3 } from 'three';

import Application from '../Application';

export default class Circuit {
  private curvePath: THREE.CurvePath<THREE.Vector3>;
  private size: number = 8192;

  constructor() {
    this._prepareLights();
    this._prepareGround();
    this._prepareLap();
    this._prepareVehicles();
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

  public _prepareVehicles() {
    let i = 0;
    for (const key in Application.world.resourceManager.driverVehicles) {
      const vehicleObject = Application.world.resourceManager.driverVehicles[key];

      vehicleObject.position.x = (i % 2) * 5;
      vehicleObject.position.z = -i * 5;

      vehicleObject.traverse((child: THREE.Object3D) => {
        child.castShadow = true;
        child.receiveShadow = true;
      });

      Application.scene.add(vehicleObject);

      i++;
    }

    // https://observablehq.com/@rveciana/three-js-object-moving-object-along-path
    const vehicle = Application.scene.getObjectByName('vehicles_HAM');

    Application.world.camera.setFollowTarget(vehicle);

    const up = new THREE.Vector3(0, 0, 1);
    const axis = new THREE.Vector3();
    let lapLocationTotal = 0;
    Application.emitter.on('tick', (delta) => {
      const lapLocation = lapLocationTotal % 1;
      const newPosition = this._getCurvePath().getPoint(lapLocation);
      const tangent = this._getCurvePath().getTangent(lapLocation);
      vehicle.position.copy(newPosition);
      axis.crossVectors(up, tangent).normalize();

      const radians = Math.acos(up.dot(tangent));

      vehicle.quaternion.setFromAxisAngle(axis, radians);

      lapLocationTotal += delta * 0.15;
    });
  }

  private _getSegments() {
    const segments: Array<THREE.Curve<THREE.Vector3>> = [];

    const anchorPoints = [
      new THREE.Vector3(-50, 0, 0.1),
      new THREE.Vector3(20, 0, 0.1),
    ];

    segments.push(
      new THREE.CubicBezierCurve3(
        anchorPoints[0],
        anchorPoints[0].clone().add(new Vector3(1, 1, 100)),
        anchorPoints[1].clone().add(new Vector3(1, 1, 100)),
        anchorPoints[1]
      )
    );

    segments.push(
      new THREE.CubicBezierCurve3(
        anchorPoints[1],
        anchorPoints[1].clone().add(new Vector3(1, 1, -100)),
        anchorPoints[0].clone().add(new Vector3(1, 1, -100)),
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
