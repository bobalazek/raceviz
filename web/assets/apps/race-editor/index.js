import 'core-js/stable';
import 'regenerator-runtime/runtime';

import React from 'react';
import ReactDOM from 'react-dom';

import {
  ToastContainer,
} from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import App from './components/App';

ReactDOM.render(
  <>
    <App />
    <ToastContainer />
  </>,
  document.getElementById('root'),
);
