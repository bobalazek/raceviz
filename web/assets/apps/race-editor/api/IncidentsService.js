import axios from 'axios';
import qs from 'qs';
import {
  toast,
} from 'react-toastify';

import store from '../store';
import {
  setLoading,
  setLoaded,
  setData,
  setError,
} from '../store/incidentsSlice';
import {
  setLoading as setRaceDriversLoading,
  setLoaded as setRaceDriversLoaded,
  setData as setRaceDriversData,
  setError as setRaceDriversError,
} from '../store/incidentRaceDriversSlice';
import {
  API_DELETE_RACES_INCIDENTS,
  API_GET_RACES_INCIDENTS,
  API_POST_RACES_INCIDENTS,
  API_PUT_RACES_INCIDENTS,
  API_GET_RACES_INCIDENTS_RACE_DRIVERS,
  API_DELETE_RACES_INCIDENTS_RACER_DRIVERS,
  API_POST_RACES_INCIDENTS_RACE_DRIVERS,
  API_PUT_RACES_INCIDENTS_RACE_DRIVERS,
} from '../api';

/* global appData */

const IncidentsService = {
  loadAll: async () => {
    const raceSlug = appData.race.slug;

    store.dispatch(setLoading(true));

    try {
      const url = API_GET_RACES_INCIDENTS
        .replace('{raceSlug}', raceSlug)
      ;

      const response = await axios.get(url);

      store.dispatch(setData(response.data.data));

      return response;
    } catch (error) {
      store.dispatch(setError(error.response.error));
    } finally {
      store.dispatch(setLoading(false));
      store.dispatch(setLoaded(true));
    }

    return null;
  },
  loadRaceDrivers: async (args) => {
    const raceIncidentId = args?.raceIncident.id;
    if (!raceIncidentId) {
      throw new Error('Please set a valid raceIncidentId');
    }

    store.dispatch(setRaceDriversLoading(true));

    try {
      const url = API_GET_RACES_INCIDENTS_RACE_DRIVERS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceIncidentId}', raceIncidentId)
      ;

      const response = await axios.get(url);

      store.dispatch(setRaceDriversData(response.data.data));

      return response;
    } catch (error) {
      store.dispatch(setRaceDriversError(error.response.error));
    } finally {
      store.dispatch(setRaceDriversLoading(false));
      store.dispatch(setRaceDriversLoaded(true));
    }

    return null;
  },
  delete: async (args) => {
    const raceSlug = appData.race.slug;

    const raceIncidentId = args?.raceIncident.id;
    if (!raceIncidentId) {
      throw new Error('Please set a valid raceIncidentId');
    }

    try {
      const url = API_DELETE_RACES_INCIDENTS
        .replace('{raceSlug}', raceSlug)
        .replace('{raceIncidentId}', raceIncidentId)
      ;

      const response = await axios.delete(url);

      toast.success('The incident was successfully deleted!');

      IncidentsService.loadAll({
        raceSlug,
      });

      return response;
    } catch (error) {
      toast.error(error.response.data.detail);
    }

    return null;
  },
  save: async (args) => {
    const raceIncidentId = args?.raceIncident?.id;
    const formData = qs.stringify(args?.formData);

    if (raceIncidentId) {
      const url = API_PUT_RACES_INCIDENTS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceIncidentId}', raceIncidentId)
      ;

      return axios.put(url, formData);
    }

    const url = API_POST_RACES_INCIDENTS
      .replace('{raceSlug}', appData.race.slug)
    ;

    return axios.post(url, formData);
  },
  deleteRaceDriver: async (args) => {
    const raceSlug = appData.race.slug;

    const storeState = store.getState();
    const raceIncident = storeState.selectedRaceIncident.data;

    const raceIncidentId = raceIncident.id;
    if (!raceIncidentId) {
      throw new Error('Please set a valid raceIncidentId');
    }

    const raceIncidentRaceDriverId = args?.raceIncidentRaceDriver.id;
    if (!raceIncidentRaceDriverId) {
      throw new Error('Please set a valid raceIncidentRaceDriverId');
    }

    try {
      const url = API_DELETE_RACES_INCIDENTS_RACER_DRIVERS
        .replace('{raceSlug}', raceSlug)
        .replace('{raceIncidentId}', raceIncidentId)
        .replace('{raceIncidentRaceDriverId}', raceIncidentRaceDriverId)
      ;

      const response = await axios.delete(url);

      toast.success('The incident race driver was successfully deleted!');

      IncidentsService.loadRaceDrivers({
        raceSlug,
        raceIncident,
      });

      return response;
    } catch (error) {
      toast.error(error.response.data.detail);
    }

    return null;
  },
  saveRaceDriver: async (args) => {
    const raceIncidentId = args?.raceIncident?.id;
    if (!raceIncidentId) {
      throw new Error('Please set a valid raceIncidentId');
    }

    const raceIncidentRaceDriverId = args?.raceIncidentRaceDriver?.id;
    const formData = qs.stringify(args?.formData);

    if (raceIncidentRaceDriverId) {
      const url = API_PUT_RACES_INCIDENTS_RACE_DRIVERS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceIncidentId}', raceIncidentId)
        .replace('{raceIncidentRaceDriverId}', raceIncidentRaceDriverId)
      ;

      return axios.put(url, formData);
    }

    const url = API_POST_RACES_INCIDENTS_RACE_DRIVERS
      .replace('{raceSlug}', appData.race.slug)
      .replace('{raceIncidentId}', raceIncidentId)
    ;

    return axios.post(url, formData);
  },
};

export default IncidentsService;
