import React, {
  useState,
} from 'react';
import PropTypes from 'prop-types';
import {
  Form,
  Button,
} from 'react-bootstrap';
import {
  toast,
} from 'react-toastify';

import {
  renderFormErrors,
} from '../../../Shared/helpers';
import DriversService from '../../../../api/DriversService';

/* global appData */

function FormEdit({
  selectedRaceDriver,
}) {
  const [raceStartingGridPosition, setRaceStartingGridPosition] = useState(selectedRaceDriver.race_driver_race_starting_grid?.position);
  const [raceStartingGridTyres, setRaceStartingGridTyres] = useState(selectedRaceDriver.race_driver_race_starting_grid?.tyres);
  const [raceStartingGridTimeDuration, setRaceStartingGridTimeDuration] = useState(selectedRaceDriver.race_driver_race_starting_grid?.time_duration);
  const [raceResultPosition, setRaceResultPosition] = useState(selectedRaceDriver.race_driver_race_result?.position);
  const [raceResultPoints, setRaceResultPoints] = useState(selectedRaceDriver.race_driver_race_result?.points);
  const [raceResultTimeDuration, setRaceResultTimeDuration] = useState(selectedRaceDriver.race_driver_race_result?.time_duration);
  const [raceResultLapsBehind, setRaceResultLapsBehind] = useState(selectedRaceDriver.race_driver_race_result?.laps_behind);
  const [raceResultStatus, setRaceResultStatus] = useState(selectedRaceDriver.race_driver_race_result?.status);
  const [raceResultStatusNote, setRaceResultStatusNote] = useState(selectedRaceDriver.race_driver_race_result?.status_note);

  const [formErrors, setFormErrors] = useState(null);
  const [formSubmitting, setFormSubmitting] = useState(false);

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    const formData = {
      raceDriverRaceStartingGrid: {
        position: raceStartingGridPosition,
        timeDuration: raceStartingGridTimeDuration,
        tyres: raceStartingGridTyres,
      },
      raceDriverRaceResult: {
        position: raceResultPosition,
        points: raceResultPoints,
        timeDuration: raceResultTimeDuration,
        lapsBehind: raceResultLapsBehind,
        status: raceResultStatus,
        statusNote: raceResultStatusNote,
      },
    };

    setFormSubmitting(true);

    try {
      await DriversService.edit({
        raceDriver: selectedRaceDriver,
        formData,
      });

      setFormErrors(null);

      toast.success('You have successfully edited the driver.');
    } catch(error) {
      if (error.response?.data?.errors) {
        setFormErrors(error.response.data.errors);

        toast.error('Please fix the errors first!');
      } else if (error.response?.data?.detail) {
        toast.error(error.response.data.detail);
      }
    } finally {
      setFormSubmitting(false);
    }
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
              value={raceStartingGridPosition ?? ''}
              onChange={(event) => { setRaceStartingGridPosition(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceStartingGrid']?.['position']}
            />
            {renderFormErrors(formErrors?.['raceDriverRaceStartingGrid']?.['position'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Tyres</Form.Label>
            <Form.Control
              as="select"
              value={raceStartingGridTyres ?? ''}
              onChange={(event) => { setRaceStartingGridTyres(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceStartingGrid']?.['tyres']}
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
            {renderFormErrors(formErrors?.['raceDriverRaceStartingGrid']?.['tyres'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Time Duration</Form.Label>
            <Form.Control
              value={raceStartingGridTimeDuration ?? ''}
              onChange={(event) => { setRaceStartingGridTimeDuration(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceStartingGrid']?.['timeDuration']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(formErrors?.['raceDriverRaceStartingGrid']?.['timeDuration'])}
          </Form.Group>
        </div>
      </div>
      <h3>Race Result</h3>
      <div className="row">
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Position</Form.Label>
            <Form.Control
              type="number"
              value={raceResultPosition ?? ''}
              onChange={(event) => { setRaceResultPosition(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['position']}
            />
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['position'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Points</Form.Label>
            <Form.Control
              type="number"
              value={raceResultPoints ?? ''}
              onChange={(event) => { setRaceResultPoints(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['points']}
            />
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['points'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Time Duration</Form.Label>
            <Form.Control
              value={raceResultTimeDuration ?? ''}
              onChange={(event) => { setRaceResultTimeDuration(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['timeDuration']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['timeDuration'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Laps Behind</Form.Label>
            <Form.Control
              type="number"
              value={raceResultLapsBehind ?? ''}
              onChange={(event) => { setRaceResultLapsBehind(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['lapsBehind']}
            />
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['lapsBehind'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Status</Form.Label>
            <Form.Control
              as="select"
              value={raceResultStatus ?? ''}
              onChange={(event) => { setRaceResultStatus(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['status']}
            >
              <option value="">-- none --</option>
              {Object.keys(appData.race_driver_statuses).map((key) => {
                const label = appData.race_driver_statuses[key];

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
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['status'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Status Note</Form.Label>
            <Form.Control
              as="textarea"
              value={raceResultStatusNote ?? ''}
              onChange={(event) => { setRaceResultStatusNote(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['statusNote']}
            />
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['statusNote'])}
          </Form.Group>
        </div>
      </div>
      {renderFormErrors(formErrors?.['*'], true)}
      <Button
        block
        size="lg"
        variant="primary"
        type="submit"
        disabled={formSubmitting}
      >
        Save
      </Button>
    </Form>
  );
}

FormEdit.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default FormEdit;
