import React from 'react';
import {
  useSelector,
  useStore,
} from 'react-redux';
import {
  Button,
}  from 'react-bootstrap';

import {
  selectData,
  setData,
} from '../../../../store/selectedRaceIncidentSlice';
import {
  setData as setDataRaceDriver,
  setModalOpen,
} from '../../../../store/selectedRaceIncidentRaceDriverSlice';
import FormSave from '../Shared/FormSave';
import TableWrapper from './TableWrapper';
import ModalRaceDriver from './ModalRaceDriver';

function Section() {
  const store = useStore();
  const selectedRaceIncident = useSelector(selectData);

  const onBackToListClick = () => {
    store.dispatch(setData(null));
  };

  const onNewRaceDriverClick = () => {
    store.dispatch(setModalOpen(true));
    store.dispatch(setDataRaceDriver(null));
  };

  return (
    <>
      <h2>
        <span>{selectedRaceIncident?.description} </span>
        <small>(lap: {selectedRaceIncident?.lap}) </small>
        <Button
          size="sm"
          onClick={onBackToListClick}
        >
          Back to List
        </Button>
      </h2>
      <FormSave selectedRaceIncident={selectedRaceIncident} />
      <hr />
      <h3>
        <span>Involved Race Drivers </span>
        <Button
          size="sm"
          onClick={onNewRaceDriverClick}
        >
          Add New Involved Race Driver
        </Button>
      </h3>
      <TableWrapper selectedRaceIncident={selectedRaceIncident} />
      <ModalRaceDriver />
    </>
  );
}

export default Section;
