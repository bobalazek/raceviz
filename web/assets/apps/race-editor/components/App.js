import React from 'react';

import DriversTableWrapper from './DriversTableWrapper';
import NewDriverForm from './NewDriverForm';
import {
  useEventListener,
} from '../hooks';
import {
  DriversService,
} from '../api';

/* global appData */

function App() {
  DriversService.load({
    slug: appData.race.slug,
  });
  useEventListener('driver-editor:new-driver', () => {
    DriversService.load({
      slug: appData.race.slug,
    });
  });

  return (
    <>
      <DriversTableWrapper />
      <NewDriverForm />
    </>
  );
}

export default App;
