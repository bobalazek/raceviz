import {
  configureStore,
} from '@reduxjs/toolkit';

import appReducer from './store/appSlice';
import driversReducer from './store/driversSlice';
import incidentsReducer from './store/incidentsSlice';
import incidentRaceDriversReducer from './store/incidentRaceDriversSlice';
import selectedRaceDriverReducer from './store/selectedRaceDriverSlice';
import selectedRaceIncidentReducer from './store/selectedRaceIncidentSlice';
import selectedRaceIncidentRaceDriverReducer from './store/selectedRaceIncidentRaceDriverSlice';

const store = configureStore({
  reducer: {
    app: appReducer,
    drivers: driversReducer,
    incidents: incidentsReducer,
    incidentRaceDrivers: incidentRaceDriversReducer,
    selectedRaceDriver: selectedRaceDriverReducer,
    selectedRaceIncident: selectedRaceIncidentReducer,
    selectedRaceIncidentRaceDriver: selectedRaceIncidentRaceDriverReducer,
  },
});

export default store;
