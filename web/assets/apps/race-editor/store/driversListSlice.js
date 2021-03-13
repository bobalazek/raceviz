
import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'driversList',
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

export const selectLoading = state => state.driversList.loading;
export const selectLoaded = state => state.driversList.loaded;
export const selectData = state => state.driversList.data;
export const selectError = state => state.driversList.error;

export default slice.reducer;
