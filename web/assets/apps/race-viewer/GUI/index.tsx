import * as React from 'react';
import * as ReactDOM from 'react-dom';
import {
  Provider,
} from 'react-redux';

import App from './components/App';
import store from './store';

let container = document.getElementById('gui');
if (!container) {
  container = document.createElement('div');
  container.id = 'gui';
  document.body.appendChild(container);
}

ReactDOM.render(
  <Provider store={store}>
    <App />
  </Provider>,
  container
);
