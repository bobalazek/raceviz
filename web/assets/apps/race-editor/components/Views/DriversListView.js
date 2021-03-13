import React from 'react';

import DriversTableWrapper from './DriversList/DriversTableWrapper';
import NewDriverFormWrapper from './DriversList/NewDriverFormWrapper';
import {
  useEventListener,
} from '../../hooks';
import {
  DriversService,
} from '../../api';

function DriversListView() {
  DriversService.load();
  useEventListener('driver-editor:new-driver', () => {
    DriversService.load();
  });

  return (
    <>
      <NewDriverFormWrapper />
      <hr />
      <DriversTableWrapper />
    </>
  );
}

export default DriversListView;
