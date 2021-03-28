import * as THREE from 'three';
import * as dat from 'dat.gui';

import Application from './Application';

import './GUI/index';

export default class GUI {
  public gui: dat.GUI;

  private vehicles: THREE.Object3D[] = [];

  constructor() {
    this.gui = new dat.GUI();

    this.gui.add(Application.world.circuit, 'playbackMultiplier', -10, 10);

    this._prepareDriverVehicles();
  }

  private _prepareDriverVehicles() {
    for (const key in Application.world.resourceManager.driverVehicles) {
      const vehicleObject = Application.world.resourceManager.driverVehicles[key];

      this.vehicles.push(vehicleObject);
    }

    setInterval(() => {
      this._update();
    }, 1000);
  }

  private _update() {
    const map = [];

    for (let i = 0; i < this.vehicles.length; i++) {
      const vehicle = this.vehicles[i];
      const raceDriver = vehicle.userData.raceDriver;
      map.push({
        code: raceDriver.season_driver.code,
        lap: vehicle.userData.lap,
        lapLocation: vehicle.userData.lapLocation,
        position: vehicle.userData.position,
      });
    }

    map.sort(function(a, b) {
      return a.position - b.position;
    });
  }
}
