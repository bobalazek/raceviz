import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

import Application from '../Application';

export default class Camera {
  private followTarget: THREE.Object3D;
  private targetPickingTolerance: number = 10;

  constructor() {
    Application.camera.far = 5000;
    Application.camera.position.set(-16, 8, -16);
    Application.camera.lookAt(0, 0, 0);

    this._prepareControls();
    this._prepareMeshPicking();
  }

  public setFollowTarget(followTarget: THREE.Object3D) {
    this.followTarget = followTarget;

    return this;
  }

  private _prepareControls() {
    const controls = new OrbitControls(Application.camera, Application.renderer.domElement);
    controls.enableDamping = true;
    controls.minDistance = 4;
    controls.maxDistance = 256;
    controls.minPolarAngle = -Math.PI;
    controls.maxPolarAngle = (Math.PI / 2) - 0.1; /* so we don't hit into the ground */

    Application.emitter.on('tick', () => {
      if (this.followTarget) {
        controls.target = this.followTarget.position; // TODO: interpolate
      }

      controls.update();
    });
  }

  private _prepareMeshPicking() {
    const raycaster = new THREE.Raycaster();
    const mouse = new THREE.Vector2();
    let pointerStartPoint: THREE.Vector2 = null;

    window.addEventListener('pointerdown', (event) => {
      pointerStartPoint = new THREE.Vector2(event.clientX, event.clientY);
    });

    window.addEventListener('pointerup', (event) => {
      const pointerEndPoint = new THREE.Vector2(event.clientX, event.clientY);
      const distance = pointerStartPoint.distanceToSquared(pointerEndPoint);
      if (distance > this.targetPickingTolerance) {
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
