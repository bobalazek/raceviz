import * as THREE from 'three';

import Application from '../Application';
import ResourceManager from './ResourceManager';
import Camera from './Camera';
import Circuit from './Circuit';
import GUI from '../GUI';

export default class World {
  public camera: Camera;
  public resourceManager: ResourceManager;
  public circuit: Circuit;
  public gui: GUI;

  constructor() {
    this.prepare();
  }

  async prepare() {
    Application.preloader.show();

    this.camera = new Camera();

    this.resourceManager = new ResourceManager();
    await this.resourceManager.prepareAsync();

    this.circuit = new Circuit();
    this.gui = new GUI();

    Application.preloader.hide();
  }
}
