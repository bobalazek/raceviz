import React, {
  useState,
} from 'react';
import PropTypes from 'prop-types';
import {
  Form,
  Button,
} from 'react-bootstrap';

import {
  renderFormErrors,
} from '../../Shared/helpers';

/* global appData */

function EditDriverForm({
  selectedRaceDriver,
}) {
  const [startingGridPosition, setStartingGridPosition] = useState(selectedRaceDriver.race_starting_grid_position);
  const [startingGridTyres, setStartingGridTyres] = useState(selectedRaceDriver.race_starting_grid_tyres);
  const [formErrors, setFormErrors] = useState(null);

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    // TODO

    setFormErrors(null); // TODO: just so the eslinter doesn't complain
  };

  return (
    <Form noValidate onSubmit={onSubmit}>
      <h3>Starting Grid</h3>
      <div className="row">
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Position</Form.Label>
            <Form.Control
              type="number"
              value={startingGridPosition ?? ''}
              onChange={(event) => { setStartingGridPosition(event.target.value) }}
              isInvalid={!!formErrors?.['startingGridPosition']}
            />
            {renderFormErrors(formErrors?.['startingGridPosition'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Tyres</Form.Label>
            <Form.Control
              as="select"
              value={startingGridTyres ?? ''}
              onChange={(event) => { setStartingGridTyres(event.target.value) }}
              isInvalid={!!formErrors?.['startingGridTyres']}
            >
              <option value="">-- none --</option>
              {Object.keys(appData.tyres).map((key) => {
                const label = appData.tyres[key];

                return (
                  <option
                    key={key}
                    value={key}
                  >
                    {label}
                  </option>
                );
              })}
            </Form.Control>
            {renderFormErrors(formErrors?.['startingGridTyres'])}
          </Form.Group>
        </div>
      </div>
      <h3>Race</h3>
      TODO
      {renderFormErrors(formErrors?.['*'], true)}
      <Button
        block
        size="lg"
        variant="primary"
        type="submit"
      >
        Save
      </Button>
    </Form>
  );
}

EditDriverForm.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default EditDriverForm;
