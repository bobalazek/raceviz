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
} from '../store/driversSlice';
import {
  API_GET_RACES_DRIVERS,
  API_GET_RACES_DRIVERS_LAPS,
  API_GET_ERGAST_RACE_DRIVER_RACE_LAPS,
  API_POST_ERGAST_RACE_DRIVER_LAPS_PREPARE_ALL,
  API_POST_RACES_DRIVERS_PREPARE_ALL,
  API_PUT_RACES_DRIVERS_LAPS,
  API_DELETE_RACES_DRIVERS,
} from '../api';

/* global appData */

const DriversService = {
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
  },
};

export default DriversService;
