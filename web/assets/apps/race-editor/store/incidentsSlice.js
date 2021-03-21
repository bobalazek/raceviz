import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'incidents',
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

export const selectLoading = state => state.incidents.loading;
export const selectLoaded = state => state.incidents.loaded;
export const selectData = state => state.incidents.data;
export const selectError = state => state.incidents.error;

export default slice.reducer;
