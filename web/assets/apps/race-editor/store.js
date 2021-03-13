import {
  configureStore,
} from '@reduxjs/toolkit';

import appReducer from './store/appSlice';
import driversReducer from './store/driversSlice';

const store = configureStore({
  reducer: {
    app: appReducer,
    drivers: driversReducer,
  },
});

export default store;
