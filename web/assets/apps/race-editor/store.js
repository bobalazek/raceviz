import {
  configureStore,
} from '@reduxjs/toolkit';

import appReducer from './store/appSlice';
import driversReducer from './store/driversSlice';
import incidentsReducer from './store/incidentsSlice';
import selectedRaceDriverReducer from './store/selectedRaceDriverSlice';
import selectedRaceIncidentReducer from './store/selectedRaceIncidentSlice';

const store = configureStore({
  reducer: {
    app: appReducer,
    drivers: driversReducer,
    incidents: incidentsReducer,
    selectedRaceDriver: selectedRaceDriverReducer,
    selectedRaceIncident: selectedRaceIncidentReducer,
  },
});

export default store;
