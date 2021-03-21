import axios from 'axios';
import qs from 'qs';
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
export const API_POST_RACES_DRIVERS_PREPARE_ALL = '/api/v1/races/{raceSlug}/drivers/prepare-all';
export const API_PUT_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}';
export const API_DELETE_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}';
export const API_GET_RACES_DRIVERS_LAPS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps';
export const API_PUT_RACES_DRIVERS_LAPS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps';
export const API_GET_ERGAST_RACE_DRIVER_RACE_LAPS = '/api/v1/ergast/{raceSlug}/{raceDriverId}/laps';
export const API_POST_ERGAST_RACE_DRIVER_LAPS_PREPARE_ALL = '/api/v1/ergast/{raceSlug}/race-driver-laps-prepare-all';

/* global appData */

export const DriversService = {
  loadAll: async () => {
    const raceSlug = appData.race.slug;

    store.dispatch(setLoading(true));

    try {
      const url = API_GET_RACES_DRIVERS
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
  loadLaps: async (args) => {
    const raceDriverId = args?.raceDriver.id;
    if (!raceDriverId) {
      throw new Error('Please set a valid raceDriverId');
    }

    const url = API_GET_RACES_DRIVERS_LAPS
      .replace('{raceSlug}', appData.race.slug)
      .replace('{raceDriverId}', raceDriverId)
    ;

    const response = await axios.get(url);

    return response.data.data;
  },
  loadLapsFromErgast: async (args) => {
    const raceDriverId = args?.raceDriver.id;
    if (!raceDriverId) {
      throw new Error('Please set a valid raceDriverId');
    }

    const url = API_GET_ERGAST_RACE_DRIVER_RACE_LAPS
      .replace('{raceSlug}', appData.race.slug)
      .replace('{raceDriverId}', raceDriverId)
    ;

    const response = await axios.get(url);

    return response.data.data;
  },
  prepareLapsFromErgastAll: async () => {
    const raceSlug = appData.race.slug;

    try {
      const url = API_POST_ERGAST_RACE_DRIVER_LAPS_PREPARE_ALL
        .replace('{raceSlug}', raceSlug)
      ;

      const response = await axios.post(url);

      toast.success('The Race Driver Laps were successfully prepared!');

      DriversService.loadAll({
        raceSlug,
      });

      return response;
    } catch (error) {
      toast.error(error.response.data.detail);
    }

    return null;
  },
  saveLaps: async(args) => {
    const raceDriverId = args?.raceDriver.id;
    if (!raceDriverId) {
      throw new Error('Please set a valid raceDriverId');
    }

    const formData = args.formData;

    const url = API_PUT_RACES_DRIVERS_LAPS
      .replace('{raceSlug}', appData.race.slug)
      .replace('{raceDriverId}', raceDriverId)
    ;

    const response = await axios.put(url, qs.stringify({
      data: JSON.stringify(formData),
    }));

    return response;
  },
  prepareAll: async() => {
    const raceSlug = appData.race.slug;

    try {
      const url = API_POST_RACES_DRIVERS_PREPARE_ALL
        .replace('{raceSlug}', raceSlug)
      ;

      const response = await axios.post(url);

      toast.success('The Race Drivers were successfully prepared!');

      DriversService.loadAll({
        raceSlug,
      });

      return response;
    } catch (error) {
      toast.error(error.response.data.detail);
    }

    return null;
  },
  delete: async (args) => {
    const raceSlug = appData.race.slug;

    const raceDriverId = args?.raceDriver.id;
    if (!raceDriverId) {
      throw new Error('Please set a valid raceDriverId');
    }

    try {
      const url = API_DELETE_RACES_DRIVERS
        .replace('{raceSlug}', raceSlug)
        .replace('{raceDriverId}', raceDriverId)
      ;

      const response = await axios.delete(url);

      toast.success('The driver was successfully deleted!');

      DriversService.loadAll({
        raceSlug,
      });

      return response;
    } catch (error) {
      toast.error(error.response.data.detail);
    }

    return null;
  }
};
