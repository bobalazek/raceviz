import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

import Application from '../Application';

export default class ResourceManager {
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

    let vehicles = [];
    let i = 0;
    for (const key in resources) {
      const resource = resources[key];
      const gltfData = await gltfLoader.loadAsync(resource);
      const vehicleMesh = <THREE.Object3D>gltfData.scene.children[0];

      vehicleMesh.name = 'vehicles_' + key;
      vehicleMesh.position.x = (i % 2) * 5;
      vehicleMesh.position.z = -i * 5;

      vehicleMesh.traverse((child: any) => {
        child.castShadow = true;
        child.receiveShadow = true;
      });

      Application.scene.add(vehicleMesh);

      vehicles.push(vehicleMesh);

      i++;
    }
  }
}
