import {
  configureStore,
} from '@reduxjs/toolkit';

import appReducer from './store/appSlice';
import driversReducer from './store/driversSlice';
import selectedRaceDriverReducer from './store/selectedRaceDriverSlice';

const store = configureStore({
  reducer: {
    app: appReducer,
    drivers: driversReducer,
    selectedRaceDriver: selectedRaceDriverReducer,
  },
});

export default store;
