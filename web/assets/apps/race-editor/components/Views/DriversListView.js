import React from 'react';

import DriversTableWrapper from './DriversList/DriversTableWrapper';
import NewDriverForm from './DriversList/NewDriverForm';
import {
  useEventListener,
} from '../../hooks';
import {
  DriversService,
} from '../../api';

/* global appData */

function DriversListView() {
  DriversService.load({
    raceSlug: appData.race.slug,
  });
  useEventListener('driver-editor:new-driver', () => {
    DriversService.load({
      raceSlug: appData.race.slug,
    });
  });

  return (
    <>
      <NewDriverForm />
      <hr />
      <DriversTableWrapper />
    </>
  );
}

export default DriversListView;
