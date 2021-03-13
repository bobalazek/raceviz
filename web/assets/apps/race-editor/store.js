import {
  configureStore,
} from '@reduxjs/toolkit';

import appReducer from './store/appSlice';
import driversListReducer from './store/driversListSlice';

const store = configureStore({
  reducer: {
    app: appReducer,
    driversList: driversListReducer,
  },
});

export default store;
