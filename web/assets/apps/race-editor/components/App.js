import React from 'react';
import {
  useSelector
} from 'react-redux';

import DriversListView from './Views/DriversListView';
import DriverEditView from './Views/DriverEditView';
import {
  selectSelectedRaceDriver,
} from '../store/appSlice';

function App() {
  const selectedRaceDriver = useSelector(selectSelectedRaceDriver);

  return (
    <>
      {!!selectedRaceDriver && (
        <DriverEditView />
      )}
      {!selectedRaceDriver && (
        <DriversListView />
      )}
    </>
  );
}

export default App;
