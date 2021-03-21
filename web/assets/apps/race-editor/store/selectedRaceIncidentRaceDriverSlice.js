import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'selectedRaceIncidentRaceDriver',
  initialState: {
    loading: false,
    loaded: false,
    data: null,
    error: null,
    modalOpen: false,
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
    setModalOpen: (state, action) => {
      state.modalOpen = action.payload;
    },
  },
});

export const {
  setLoading,
  setLoaded,
  setData,
  setError,
  setModalOpen,
} = slice.actions;

export const selectLoading = state => state.selectedRaceIncidentRaceDriver.loading;
export const selectLoaded = state => state.selectedRaceIncidentRaceDriver.loaded;
export const selectData = state => state.selectedRaceIncidentRaceDriver.data;
export const selectError = state => state.selectedRaceIncidentRaceDriver.error;
export const selectModalOpen = state => state.selectedRaceIncidentRaceDriver.modalOpen;

export default slice.reducer;
