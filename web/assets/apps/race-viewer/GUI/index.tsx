import * as React from 'react';
import * as ReactDOM from 'react-dom';

import App from './components/App';

let container = document.getElementById('gui');
if (!container) {
  container = document.createElement('div');
  document.body.appendChild(container);
}

ReactDOM.render(
  <App />,
  container
);
