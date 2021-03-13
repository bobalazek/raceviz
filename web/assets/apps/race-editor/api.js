import axios from 'axios';
import {
  toast,
} from 'react-toastify';

import store from './store';
import {
  setLoading,
  setLoaded,
  setData,
  setError,
} from './store/driversSlice';

export const API_GET_SEASONS = '/api/v1/seasons';
export const API_GET_SEASONS_DRIVERS = '/api/v1/seasons/{seasonSlug}/drivers';
export const API_GET_SEASONS_TEAMS = '/api/v1/seasons/{seasonSlug}/teams';
export const API_GET_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers';
export const API_POST_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers';
export const API_DELETE_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}';

export const DriversService = {
  load: async (args) => {
    const slug = args?.slug;
    if (!slug) {
      throw new Error('Please set a valid slug');
    }

    store.dispatch(setLoading(true));

    try {
      const url = API_GET_RACES_DRIVERS
        .replace('{raceSlug}', slug)
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
  delete: async (args) => {
    const slug = args?.slug;
    if (!slug) {
      throw new Error('Please set a valid slug');
    }

    const raceDriverId = args?.raceDriver.id;
    if (!raceDriverId) {
      throw new Error('Please set a valid raceDriverId');
    }

    try {
      const url = API_DELETE_RACES_DRIVERS
        .replace('{raceSlug}', slug)
        .replace('{raceDriverId}', raceDriverId)
      ;

      await axios.delete(url);

      toast.success('The driver was successfully deleted!');

      DriversService.load({
        slug,
      });
    } catch (error) {
      toast.error(error.response.data.detail);
    }
  }
};
