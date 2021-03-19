import React, {
  useState,
} from 'react';
import PropTypes from 'prop-types';
import {
  Form,
  Button,
} from 'react-bootstrap';
import axios from 'axios';
import qs from 'qs';
import {
  toast,
} from 'react-toastify';

import {
  API_PUT_RACES_DRIVERS,
} from '../../../api';
import {
  renderFormErrors,
} from '../../Shared/helpers';

/* global appData */

function EditDriverForm({
  selectedRaceDriver,
}) {
  const [raceStartingGridPosition, setRaceStartingGridPosition] = useState(selectedRaceDriver.race_driver_race_starting_grid?.position);
  const [raceStartingGridTyres, setRaceStartingGridTyres] = useState(selectedRaceDriver.race_driver_race_starting_grid?.tyres);
  const [raceStartingGridTime, setRaceStartingGridTime] = useState(selectedRaceDriver.race_driver_race_starting_grid?.time);
  const [raceResultPosition, setRaceResultPosition] = useState(selectedRaceDriver.race_driver_race_result?.position);
  const [raceResultPoints, setRaceResultPoints] = useState(selectedRaceDriver.race_driver_race_result?.points);
  const [raceResultTime, setRaceResultTime] = useState(selectedRaceDriver.race_driver_race_result?.time);
  const [raceResultLapsBehind, setRaceResultLapsBehind] = useState(selectedRaceDriver.race_driver_race_result?.laps_behind);
  const [raceResultStatus, setRaceResultStatus] = useState(selectedRaceDriver.race_driver_race_result?.status);
  const [raceResultStatusNote, setRaceResultStatusNote] = useState(selectedRaceDriver.race_driver_race_result?.status_note);

  const [formErrors, setFormErrors] = useState(null);
  const [formSubmitting, setFormSubmitting] = useState(false);

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      const url = API_PUT_RACES_DRIVERS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceDriverId}', selectedRaceDriver.id)
      ;

      await axios.put(url, qs.stringify({
        raceDriverRaceStartingGrid: {
          position: raceStartingGridPosition,
          time: raceStartingGridTime,
          tyres: raceStartingGridTyres,
        },
        raceDriverRaceResult: {
          position: raceResultPosition,
          points: raceResultPoints,
          time: raceResultTime,
          lapsBehind: raceResultLapsBehind,
          status: raceResultStatus,
          statusNote: raceResultStatusNote,
        },
      }));

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
            <Form.Label>Time</Form.Label>
            <Form.Control
              value={raceStartingGridTime ?? ''}
              onChange={(event) => { setRaceStartingGridTime(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceStartingGrid']?.['time']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(formErrors?.['raceDriverRaceStartingGrid']?.['time'])}
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
            <Form.Label>Time</Form.Label>
            <Form.Control
              value={raceResultTime ?? ''}
              onChange={(event) => { setRaceResultTime(event.target.value) }}
              isInvalid={!!formErrors?.['raceDriverRaceResult']?.['time']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(formErrors?.['raceDriverRaceResult']?.['time'])}
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

EditDriverForm.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default EditDriverForm;
