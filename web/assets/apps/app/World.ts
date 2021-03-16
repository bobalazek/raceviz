import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

import Application from './Application';

// Resources
import carsMercedes2021Resource from './Resources/models/cars/mercedes_2021.glb';

export default class World {
  constructor() {
    Application.preloader.show();

    this.prepareResources();
    this.prepareCamera();
    this.prepareControls();
    this.prepareLights();
    this.prepareGround();

    Application.preloader.hide();
  }

  async prepareResources() {
    const resources = [
      carsMercedes2021Resource,
    ];

    await Application.loader.loadBatch(resources);

    const carLoaderResource = await Application.loader.load(carsMercedes2021Resource);
    const carMesh = carLoaderResource.data.scene.children[0];

    Application.scene.add(carMesh);

    Application.emitter.on('tick', () => {
      carMesh.position.z += 0.01;
    });
  }

  prepareCamera() {
    Application.camera.position.set(0, 32, 0);
    Application.camera.lookAt(0, 0, 0);
  }

  prepareControls() {
    const controls = new OrbitControls(Application.camera, Application.renderer.domElement);
    controls.enableDamping = true;
    controls.minPolarAngle = -Math.PI;
    controls.maxPolarAngle = (Math.PI / 2) - 0.1; /* so we don't hit into the ground */

    Application.emitter.on('tick', () => {
      controls.update();
    });
  }

  prepareLights() {
    const light = new THREE.HemisphereLight(0xffffff, 0xffffff, 1);
    light.position.set(0, 50, 0);

    Application.scene.add(light);
  }

  prepareGround() {
    const groundGeo = new THREE.PlaneGeometry(1024, 1024);
    const groundMat = new THREE.MeshLambertMaterial({ color: 0xffffff });
    groundMat.color.setHSL(0.095, 1, 0.75);

    const ground = new THREE.Mesh(groundGeo, groundMat);
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;

    Application.scene.add(ground);
  }
}
