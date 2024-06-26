import {
  useEffect,
  useState,
  useRef,
} from 'react';
import axios from 'axios';

import {
  API_GET_RACES_DRIVERS,
  API_GET_SEASONS_DRIVERS,
  API_GET_SEASONS_TEAMS,
} from './api';

/* global appData */

export const useRaceDriversFetch = () => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const seasonSlug = appData.race.season.slug;
  const raceSlug = appData.race.slug;

  useEffect(async () => {
    setLoading(true);

    try {
      const url = API_GET_RACES_DRIVERS
        .replace('{seasonSlug}', seasonSlug)
        .replace('{raceSlug}', raceSlug)
      ;

      const response = await axios.get(url);
      setData(response.data.data);
    } catch (error) {
      setError(error.response.error);
    } finally {
      setLoading(false);
      setLoaded(true);
    }
  }, [seasonSlug, raceSlug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};

export const useSeasonsDriversFetch = () => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const seasonSlug = appData.race.season.slug;

  useEffect(async () => {
    setLoading(true);

    try {
      const url = API_GET_SEASONS_DRIVERS
        .replace('{seasonSlug}', seasonSlug)
      ;

      const response = await axios.get(url);
      setData(response.data.data);
    } catch (error) {
      setError(error.response.error);
    } finally {
      setLoading(false);
      setLoaded(true);
    }
  }, [seasonSlug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};

export const useSeasonsTeamsFetch = () => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const seasonSlug = appData.race.season.slug;

  useEffect(async () => {
    setLoading(true);

    try {
      const url = API_GET_SEASONS_TEAMS
        .replace('{seasonSlug}', seasonSlug)
      ;

      const response = await axios.get(url);
      setData(response.data.data);
    } catch (error) {
      setError(error.response.error);
    } finally {
      setLoading(false);
      setLoaded(true);
    }
  }, [seasonSlug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};

// https://usehooks.com/useEventListener/
export const useEventListener = (eventName, handler, element = window) => {
  const savedHandler = useRef();

  useEffect(() => {
    savedHandler.current = handler;
  }, [handler]);

  useEffect(
    () => {
      const isSupported = element && element.addEventListener;
      if (!isSupported) return;

      const eventListener = event => savedHandler.current(event);

      element.addEventListener(eventName, eventListener);

      return () => {
        element.removeEventListener(eventName, eventListener);
      };
    },
    [eventName, element]
  );
};
