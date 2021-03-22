import * as THREE from 'three';

import Application from '../Application';
import ResourceManager from './ResourceManager';
import Camera from './Camera';

export default class World {
  public resourceManager: ResourceManager;
  public camera: Camera;

  constructor() {
    this.prepare();
  }

  async prepare() {
    Application.preloader.show();

    this.resourceManager = new ResourceManager();
    await this.resourceManager.prepareAsync();

    this.camera = new Camera();

    this._prepareLights();
    this._prepareGround();

    Application.preloader.hide();
  }

  private _prepareLights() {
    const hemisphereLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 3);
    hemisphereLight.position.set(0, 50, 0);

    Application.scene.add(hemisphereLight);
  }

  private _prepareGround() {
    const groundGeometry = new THREE.PlaneGeometry(1024, 1024);
    const groundMaterial = new THREE.MeshPhongMaterial({ color: 0xb3b3b3 });

    const ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.name = 'ground';
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;

    Application.scene.add(ground);
  }
}
