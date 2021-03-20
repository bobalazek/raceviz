import Application from './Application';

import './Resources/styles/index.scss';

const canvasElement = <HTMLCanvasElement>document.getElementById('canvas');
const parameters = (window as any).appData;

Application.boot({
  canvasElement,
  debug: true,
}, parameters);
