import axios from 'axios';
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
  API_DELETE_RACES_INCIDENTS,
  API_GET_RACES_INCIDENTS,
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
};

export default IncidentsService;
