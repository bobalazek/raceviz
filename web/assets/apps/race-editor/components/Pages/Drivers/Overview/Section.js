import React from 'react';

import TableWrapper from './TableWrapper';
import FormNew from './FormNew';
import {
  useEventListener,
} from '../../../../hooks';
import DriversService from '../../../../api/DriversService';

function Section() {
  DriversService.loadAll();
  useEventListener('driver-editor:new-driver', () => {
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
