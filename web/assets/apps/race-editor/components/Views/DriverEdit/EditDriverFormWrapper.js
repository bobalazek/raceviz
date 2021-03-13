import React from 'react';
import {
  useStore,
  useSelector,
} from 'react-redux';
import {
  Button,
} from 'react-bootstrap';

import EditDriverForm from './EditDriverForm';
import {
  setSelectedRaceDriver,
  selectSelectedRaceDriver,
} from '../../../store/appSlice';

function EditDriverFormWrapper() {
  const store = useStore();
  const selectedRaceDriver = useSelector(selectSelectedRaceDriver);

  const onBackToListClick = () => {
    store.dispatch(setSelectedRaceDriver(null));
  };


  return (
    <div>
      <h2>
        <span>{selectedRaceDriver.driver.name} </span>
        <small>({selectedRaceDriver.team.name}) </small>
        <Button
          size="sm"
          onClick={onBackToListClick}
        >
          Back to List
        </Button>
      </h2>
      <EditDriverForm />
    </div>
  )
}

export default EditDriverFormWrapper;
