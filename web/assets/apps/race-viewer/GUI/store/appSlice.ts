import {
  createSlice,
} from '@reduxjs/toolkit';

export const slice = createSlice({
  name: 'app',
  initialState: {
    positions: [],
  },
  reducers: {
    setPositions: (state, action) => {
      state.positions = action.payload;
    },
  },
});

export const {
  setPositions,
} = slice.actions;

export const selectPositions = state => state.app.positions;

export default slice.reducer;
