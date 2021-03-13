import axios from 'axios';

import store from './store';
import {
  setLoading,
  setLoaded,
  setData,
  setError,
} from './store/driversSlice';

export const API_GET_SEASONS = '/api/v1/seasons';
export const API_GET_SEASONS_DRIVERS = '/api/v1/seasons/{slug}/drivers';
export const API_GET_SEASONS_TEAMS = '/api/v1/seasons/{slug}/teams';
export const API_GET_RACES_DRIVERS = '/api/v1/races/{slug}/drivers';
export const API_POST_RACES_DRIVERS = '/api/v1/races/{slug}/drivers';

export const DriversService = {
  load: async (args) => {
    const slug = args?.slug;
    if (!slug) {
      throw new Error('Please set a valid slug');
    }

    store.dispatch(setLoading(true));

    try {
      const url = API_GET_RACES_DRIVERS
        .replace('{slug}', slug)
      ;

      const response = await axios.get(url);

      store.dispatch(setData(response.data.data));
    } catch (error) {
      store.dispatch(setError(error.response.error));
    } finally {
      store.dispatch(setLoading(false));
      store.dispatch(setLoaded(true));
    }
  },
};
