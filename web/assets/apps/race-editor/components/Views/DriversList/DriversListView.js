import React from 'react';

import DriversTableWrapper from './DriversTableWrapper';
import NewDriverFormWrapper from './NewDriverFormWrapper';
import {
  useEventListener,
} from '../../../hooks';
import {
  DriversService,
} from '../../../api';

function DriversListView() {
  DriversService.loadAll();
  useEventListener('driver-editor:new-driver', () => {
    DriversService.loadAll();
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
