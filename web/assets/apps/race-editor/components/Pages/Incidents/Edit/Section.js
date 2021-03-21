import React from 'react';
import PropTypes from 'prop-types';
import {
  useStore,
} from 'react-redux';
import {
  Button,
}  from 'react-bootstrap';

import {
  setData,
} from '../../../../store/selectedRaceIncidentSlice';
import FormSave from '../Shared/FormSave';

function Section({
  selectedRaceIncident,
}) {
  const store = useStore();

  const onBackToListClick = () => {
    store.dispatch(setData(null));
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
      <h3>Involved Race Drivers</h3>
      TODO
    </>
  );
}

Section.propTypes = {
  selectedRaceIncident: PropTypes.object,
};

export default Section;
