import React from 'react';

import TableWrapper from './TableWrapper';
import FormNew from './FormNew';
import DriversService from '../../../../api/DriversService';
import {
  useEventListener,
} from '../../../../hooks';

function Section() {
  DriversService.loadAll();
  useEventListener('race-editor:reload-drivers', () => {
    DriversService.loadAll();
  });

  return (
    <>
      <h2>New Driver</h2>
      <FormNew />
      <hr />
      <TableWrapper />
    </>
  );
}

export default Section;
