
import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'app',
  initialState: {
    user: null,
    selectedRaceDriver: null,
  },
  reducers: {
    setUser: (state, action) => {
      state.user = action.payload;
    },
    setSelectedRaceDriver: (state, action) => {
      state.selectedRaceDriver = action.payload;
    },
  },
});

export const {
  setUser,
  setSelectedRaceDriver,
} = slice.actions;

export const selectUser = state => state.app.user;
export const selectSelectedRaceDriver = state => state.app.selectedRaceDriver;

export default slice.reducer;
