import * as THREE from 'three';

import Application from '../Application';
import ResourceManager from './ResourceManager';
import Camera from './Camera';
import Circuit from './Circuit';

export default class World {
  public resourceManager: ResourceManager;
  public camera: Camera;
  public circuit: Circuit;

  constructor() {
    this.prepare();
  }

  async prepare() {
    Application.preloader.show();

    this.resourceManager = new ResourceManager();
    await this.resourceManager.prepareAsync();

    this.camera = new Camera();
    this.circuit = new Circuit();

    Application.preloader.hide();
  }
}
