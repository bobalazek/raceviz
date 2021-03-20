import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

import Application from './Application';

// Resources
import carsMercedes2021Resource from './Resources/models/cars/mercedes_2021.glb';
import carsRedBull2021Resource from './Resources/models/cars/red_bull_2021.glb';
import carsFerrari2021Resource from './Resources/models/cars/ferrari_2021.glb';
import carsMclaren2021Resource from './Resources/models/cars/mclaren_2021.glb';
import carsAstonMartin2021Resource from './Resources/models/cars/aston_martin_2021.glb';
import carsAlpine2021Resource from './Resources/models/cars/alpine_2021.glb';
import carsAlphatauri2021Resource from './Resources/models/cars/alphatauri_2021.glb';
import carsAlphaRomeo2021Resource from './Resources/models/cars/alfa_romeo_2021.glb';
import carsHaas2021Resource from './Resources/models/cars/haas_2021.glb';
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
    const resources = {
      mercedes: carsMercedes2021Resource,
      redBull: carsRedBull2021Resource,
      ferrari: carsFerrari2021Resource,
      mclaren: carsMclaren2021Resource,
      astonMartin: carsAstonMartin2021Resource,
      alpine: carsAlpine2021Resource,
      alphatauri: carsAlphatauri2021Resource,
      alphaRomeo: carsAlphaRomeo2021Resource,
      hass: carsHaas2021Resource,
      williams: carsWilliams2021Resource,
    };

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

    this.followTarget = vehicles[0];

    const speed = 0.1;
    Application.emitter.on('tick', () => {
      for (let i = 0; i < vehicles.length; i++) {
        const vehicleMesh = <THREE.Object3D>vehicles[i];

        const vehicleMeshWheelFL = vehicleMesh.getObjectByName('Bone_Wheel_FrontLeft');
        const vehicleMeshWheelFR = vehicleMesh.getObjectByName('Bone_Wheel_FrontRight');
        const vehicleMeshWheelRL = vehicleMesh.getObjectByName('Bone_Wheel_RearLeft');
        const vehicleMeshWheelRR = vehicleMesh.getObjectByName('Bone_Wheel_RearRight');

        vehicleMesh.position.z += speed;

        const wheelSpin = speed * 2;
        vehicleMeshWheelFL.rotateY(wheelSpin);
        vehicleMeshWheelFR.rotateY(-wheelSpin);
        vehicleMeshWheelRL.rotateY(wheelSpin);
        vehicleMeshWheelRR.rotateY(-wheelSpin);
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
        controls.target = this.followTarget.position; // TODO: interpolate
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
    let pointerStartPoint: THREE.Vector2 = null;

    window.addEventListener('pointerdown', (event) => {
      pointerStartPoint = new THREE.Vector2(event.clientX, event.clientY);
    });

    window.addEventListener('pointerup', (event) => {
      const pointerEndPoint = new THREE.Vector2(event.clientX, event.clientY);
      const distance = pointerStartPoint.distanceToSquared(pointerEndPoint);
      if (distance > 5) {
        return;
      }

      mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
      mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

      raycaster.setFromCamera(mouse, Application.camera);

      let vehicleBoxesMap = [];
      let vehicleBoxes = [];
      for (let i = 0; i < Application.scene.children.length; i++) {
        const child = Application.scene.children[i];
        if (!child.name.startsWith('vehicles_')) {
          continue;
        }

        vehicleBoxes.push(
          new THREE.Box3().setFromObject(child)
        );
        vehicleBoxesMap.push(
          child.name
        );
      }

      for (let i = 0; i < vehicleBoxes.length; i++) {
        const vehicleBox = vehicleBoxes[i];
        if (!raycaster.ray.intersectsBox(vehicleBox)) {
          continue;
        }

        this.followTarget = Application.scene.getObjectByName(vehicleBoxesMap[i]);

        break;
      }
    });
  }
}
