import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'incidentRaceDrivers',
  initialState: {
    loading: false,
    loaded: false,
    data: [],
    error: null,
  },
  reducers: {
    setLoading: (state, action) => {
      state.loading = action.payload;
    },
    setLoaded: (state, action) => {
      state.loaded = action.payload;
    },
    setData: (state, action) => {
      state.data = action.payload;
    },
    setError: (state, action) => {
      state.error = action.payload;
    },
  },
});

export const {
  setLoading,
  setLoaded,
  setData,
  setError,
} = slice.actions;

export const selectLoading = state => state.incidentRaceDrivers.loading;
export const selectLoaded = state => state.incidentRaceDrivers.loaded;
export const selectData = state => state.incidentRaceDrivers.data;
export const selectError = state => state.incidentRaceDrivers.error;

export default slice.reducer;
