import * as THREE from 'three';

import Application from '../Application';

export default class Circuit {
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
    // https://observablehq.com/@rveciana/three-js-object-moving-object-along-path

    const curvePath = new THREE.CurvePath<THREE.Vector3>();

    curvePath.add(
      new THREE.CubicBezierCurve3(
        new THREE.Vector3(-50, 0, 0),
        new THREE.Vector3(-5, 0, -10),
        new THREE.Vector3(20, 0, -40),
        new THREE.Vector3(20, 0, 0)
      ),
    );

    curvePath.closePath();

    const points = curvePath.getPoints(50);

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
  }
}
