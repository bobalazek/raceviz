import Application from './Application';

import carsMercedes2021Resource from './Resources/models/cars/mercedes_2021.glb';

export default class World {
  constructor() {
    Application.preloader.show();

    const resources = [
      carsMercedes2021Resource,
    ];

    Application.loader.loadBatch(resources).then(() => {
      console.log('All resources loaded!');

      Application.preloader.hide();
    });
  }
}
