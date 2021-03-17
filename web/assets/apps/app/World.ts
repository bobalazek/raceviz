import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

import Application from './Application';

// Resources
import carsMercedes2021Resource from './Resources/models/cars/mercedes_2021.glb';

export default class World {
  private followTarget: THREE.Object3D;

  constructor() {
    this.prepare();
  }

  async prepare() {
    Application.preloader.show();

    await this.prepareResources();
    await this.prepareCamera();
    await this.prepareControls();
    await this.prepareLights();
    await this.prepareGround();

    Application.preloader.hide();
  }

  async prepareResources() {
    const gltfLoader = new GLTFLoader(Application.loadingManager);
    const gltfData = await gltfLoader.loadAsync(carsMercedes2021Resource);
    const carMesh = <THREE.Object3D>gltfData.scene.children[0];

    Application.scene.add(carMesh);

    this.followTarget = carMesh;

    const carMeshWheelFL = carMesh.getObjectByName('Bone_Wheel_FrontLeft');
    const carMeshWheelFR = carMesh.getObjectByName('Bone_Wheel_FrontRight');
    const carMeshWheelRL = carMesh.getObjectByName('Bone_Wheel_RearLeft');
    const carMeshWheelRR = carMesh.getObjectByName('Bone_Wheel_RearRight');

    const speed = 0.1;
    Application.emitter.on('tick', () => {
      carMesh.position.z += speed;

      const wheelSpin = speed * 2;
      carMeshWheelFL.rotateY(wheelSpin);
      carMeshWheelFR.rotateY(-wheelSpin);
      carMeshWheelRL.rotateY(wheelSpin);
      carMeshWheelRR.rotateY(-wheelSpin);
    });
  }

  async prepareCamera() {
    Application.camera.position.set(-16, 8, -16);
    Application.camera.lookAt(0, 0, 0);
  }

  async prepareControls() {
    const controls = new OrbitControls(Application.camera, Application.renderer.domElement);
    controls.enableDamping = true;
    controls.minDistance = 8;
    controls.maxDistance = 24;
    controls.minPolarAngle = -Math.PI;
    controls.maxPolarAngle = (Math.PI / 2) - 0.1; /* so we don't hit into the ground */

    Application.emitter.on('tick', () => {
      if (this.followTarget) {
        controls.target = this.followTarget.position;
      }

      controls.update();
    });
  }

  async prepareLights() {
    const hemisphereLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 1);
    hemisphereLight.position.set(0, 50, 0);

    Application.scene.add(hemisphereLight);
  }

  async prepareGround() {
    const groundGeometry = new THREE.PlaneGeometry(1024, 1024);
    const groundMaterial = new THREE.MeshLambertMaterial({ color: 0xb3b3b3 });

    const ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;

    Application.scene.add(ground);
  }
}
