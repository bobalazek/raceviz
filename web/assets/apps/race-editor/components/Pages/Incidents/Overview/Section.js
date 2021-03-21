import React from 'react';
import PropTypes from 'prop-types';

import TableWrapper from './TableWrapper';
import FormSave from '../Shared/FormSave';
import IncidentsService from '../../../../api/IncidentsService';
import {
  useEventListener,
} from '../../../../hooks';

function Section({
  selectedRaceDriver,
}) {
  IncidentsService.loadAll();
  useEventListener('race-editor:reload-incidents', () => {
    IncidentsService.loadAll();
  });

  return (
    <>
      <h2>New Incident</h2>
      <FormSave selectedRaceDriver={selectedRaceDriver} />
      <hr />
      <TableWrapper />
    </>
  );
}

Section.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default Section;
