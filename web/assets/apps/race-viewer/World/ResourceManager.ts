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
    const resources = {};
    for (const key in Application.parameters.race_drivers) {
      const raceDriver = Application.parameters.race_drivers[key];
      const vehicleModelUrl = raceDriver.vehicle_model_url;

      resources[raceDriver.season_driver.code] = vehicleModelUrl;
    }

    let i = 0;
    for (const key in resources) {
      const resource = resources[key];
      const gltfData = await gltfLoader.loadAsync(resource);
      const vehicleObject = <THREE.Object3D>gltfData.scene.children[0];

      vehicleObject.name = 'vehicles_' + key;
      vehicleObject.position.x = (i % 2) * 5;
      vehicleObject.position.z = -i * 5;

      vehicleObject.traverse((child: any) => {
        child.castShadow = true;
        child.receiveShadow = true;
      });

      this.driverVehicles[key] = vehicleObject;

      Application.scene.add(vehicleObject);

      i++;
    }
  }
}
