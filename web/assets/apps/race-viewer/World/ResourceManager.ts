import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

import Application from '../Application';

export default class ResourceManager {
  public driverVehicles: {[driverCode: string]: THREE.Object3D} = {};

  constructor() {
    // Nothing to do here, as we will just call the prepareAsync below in the parent class
  }

  async prepareAsync() {
    const gltfLoader = new GLTFLoader(Application.loadingManager);

    for (let i = 0; i < Application.parameters.race_drivers.length; i++) {
      const raceDriver = Application.parameters.race_drivers[i];
      const key = raceDriver.season_driver.code;
      const vehicleModelUrl = raceDriver.vehicle_model_url;

      const gltfData = await gltfLoader.loadAsync(vehicleModelUrl);
      const vehicleObject = <THREE.Object3D>gltfData.scene.children[0];

      vehicleObject.name = 'vehicles_' + key;
      vehicleObject.userData.raceDriver = raceDriver;
      vehicleObject.userData.laps = Application.parameters.race_driver_laps[raceDriver.id];

      this.driverVehicles[key] = vehicleObject;
    }
  }
}
