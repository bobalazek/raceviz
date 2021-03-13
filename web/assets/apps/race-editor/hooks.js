import {
  useEffect,
  useState,
} from 'react';
import axios from 'axios';

import {
  API_GET_RACES_DRIVERS,
  API_GET_SEASONS_DRIVERS,
  API_GET_SEASONS_TEAMS,
} from './api';

export const useRacesDriversFetch = (args) => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const slug = args?.slug;

  useEffect(() => {
    setLoading(true);

    (async () => {
      try {
        const url = API_GET_RACES_DRIVERS
          .replace('{slug}', slug)
        ;

        const response = await axios.get(url);
        setData(response.data.data);
      } catch (error) {
        setError(error.response.error);
      } finally {
        setLoading(false);
        setLoaded(true);
      }
    })();
  }, [slug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};

export const useSeasonsDriversFetch = (args) => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const slug = args?.slug;

  useEffect(() => {
    setLoading(true);

    (async () => {
      try {
        const url = API_GET_SEASONS_DRIVERS
          .replace('{slug}', slug)
        ;

        const response = await axios.get(url);
        setData(response.data.data);
      } catch (error) {
        setError(error.response.error);
      } finally {
        setLoading(false);
        setLoaded(true);
      }
    })();
  }, [slug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};

export const useSeasonsTeamsFetch = (args) => {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(false);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const slug = args?.slug;

  useEffect(() => {
    setLoading(true);

    (async () => {
      try {
        const url = API_GET_SEASONS_TEAMS
          .replace('{slug}', slug)
        ;

        const response = await axios.get(url);
        setData(response.data.data);
      } catch (error) {
        setError(error.response.error);
      } finally {
        setLoading(false);
        setLoaded(true);
      }
    })();
  }, [slug]);

  return {
    loading,
    loaded,
    data,
    error,
  };
};
