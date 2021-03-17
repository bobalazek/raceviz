import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

import Application from './Application';

// Resources
import carsMercedes2021Resource from './Resources/models/cars/mercedes_2021.glb';
import carsFerrari2021Resource from './Resources/models/cars/ferrari_2021.glb';
import carsAlpine2021Resource from './Resources/models/cars/alpine_2021.glb';
import carsAlphatauri2021Resource from './Resources/models/cars/alphatauri_2021.glb';
import carsAlphaRomeo2021Resource from './Resources/models/cars/alfa_romeo_2021.glb';
import carsAstonMartin2021Resource from './Resources/models/cars/aston_martin_2021.glb';
import carsMclaren2021Resource from './Resources/models/cars/mclaren_2021.glb';
import carsHaas2021Resource from './Resources/models/cars/haas_2021.glb';
import carsRedBull2021Resource from './Resources/models/cars/red_bull_2021.glb';
import carsWilliams2021Resource from './Resources/models/cars/williams_2021.glb';

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
    await this.prepareMeshPicking();

    Application.preloader.hide();
  }

  async prepareResources() {
    const gltfLoader = new GLTFLoader(Application.loadingManager);
    const resources = [
      carsMercedes2021Resource,
      carsFerrari2021Resource,
      carsAlpine2021Resource,
      carsAlphaRomeo2021Resource,
      carsAlphatauri2021Resource,
      carsAstonMartin2021Resource,
      carsMclaren2021Resource,
      carsHaas2021Resource,
      carsRedBull2021Resource,
      carsWilliams2021Resource,
    ];

    let cars = [];
    for (let i = 0; i < resources.length; i++) {
      const resource = resources[i];
      const gltfData = await gltfLoader.loadAsync(resource);
      const carMesh = <THREE.Object3D>gltfData.scene.children[0];

      carMesh.position.x = (i % 2) * 5;
      carMesh.position.z = -i * 5;

      carMesh.traverse((child: any) => {
        child.castShadow = true;
        child.receiveShadow = true;
      });

      Application.scene.add(carMesh);

      cars.push(carMesh);
    }

    this.followTarget = cars[4];

    const speed = 0.1;
    Application.emitter.on('tick', () => {
      for (let i = 0; i < cars.length; i++) {
        const carMesh = <THREE.Object3D>cars[i];

        const carMeshWheelFL = carMesh.getObjectByName('Bone_Wheel_FrontLeft');
        const carMeshWheelFR = carMesh.getObjectByName('Bone_Wheel_FrontRight');
        const carMeshWheelRL = carMesh.getObjectByName('Bone_Wheel_RearLeft');
        const carMeshWheelRR = carMesh.getObjectByName('Bone_Wheel_RearRight');

        carMesh.position.z += speed;

        const wheelSpin = speed * 2;
        carMeshWheelFL.rotateY(wheelSpin);
        carMeshWheelFR.rotateY(-wheelSpin);
        carMeshWheelRL.rotateY(wheelSpin);
        carMeshWheelRR.rotateY(-wheelSpin);
      }
    });
  }

  async prepareCamera() {
    Application.camera.far = 5000;
    Application.camera.position.set(-16, 8, -16);
    Application.camera.lookAt(0, 0, 0);
  }

  async prepareControls() {
    const controls = new OrbitControls(Application.camera, Application.renderer.domElement);
    controls.enableDamping = true;
    controls.minDistance = 4;
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
    const hemisphereLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 3);
    hemisphereLight.position.set(0, 50, 0);

    Application.scene.add(hemisphereLight);
  }

  async prepareGround() {
    const groundGeometry = new THREE.PlaneGeometry(1024, 1024);
    const groundMaterial = new THREE.MeshPhongMaterial({ color: 0xb3b3b3 });

    const ground = new THREE.Mesh(groundGeometry, groundMaterial);
    ground.name = 'ground';
    ground.rotation.x = -Math.PI / 2;
    ground.receiveShadow = true;

    Application.scene.add(ground);
  }

  async prepareMeshPicking() {
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();

    window.addEventListener('click', (event) => {
      mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
      mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

      raycaster.setFromCamera(mouse, Application.camera);

      const intersects = raycaster.intersectObjects(Application.scene.children, true);
      if (intersects.length === 0) {
        return;
      }

      for (let i = 0; i < intersects.length; i++) {
        const intersection = intersects[i];
        if (intersection.object.name === 'ground') {
          continue
        }

        // TODO: https://discourse.threejs.org/t/raycasting-a-gltf-model-without-using-recursive-option/21307/3
        // this.followTarget = intersection.object;

        break;
      }
    });
  }
}
