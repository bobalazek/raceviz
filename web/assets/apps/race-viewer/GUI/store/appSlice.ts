import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'app',
  initialState: {
    user: [],
  },
  reducers: {
    setPositions: (state, action) => {
      (<any>state).positions = action.payload; // temporary fix
    },
  },
});

export const {
  setPositions,
} = slice.actions;

export const selectPositions = state => state.app.positions;

export default slice.reducer;
