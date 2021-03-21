import React from 'react';

import TableWrapper from './TableWrapper';
import FormSave from '../Shared/FormSave';
import IncidentsService from '../../../../api/IncidentsService';
import {
  useEventListener,
} from '../../../../hooks';

function Section() {
  IncidentsService.loadAll();
  useEventListener('race-editor:reload-incidents', () => {
    IncidentsService.loadAll();
  });

  return (
    <>
      <h2>New Incident</h2>
      <FormSave />
      <hr />
      <TableWrapper />
    </>
  );
}

export default Section;
