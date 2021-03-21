import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'selectedRaceDriver',
  initialState: {
    loading: false,
    loaded: false,
    data: null,
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

export const selectLoading = state => state.selectedRaceDriver.loading;
export const selectLoaded = state => state.selectedRaceDriver.loaded;
export const selectData = state => state.selectedRaceDriver.data;
export const selectError = state => state.selectedRaceDriver.error;

export default slice.reducer;
