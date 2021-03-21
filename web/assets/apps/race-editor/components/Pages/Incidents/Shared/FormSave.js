import React, {
  useState,
} from 'react';
import PropTypes from 'prop-types';
import {
  Form as Form,
  Button,
} from 'react-bootstrap';
import axios from 'axios';
import qs from 'qs';
import {
  toast,
} from 'react-toastify';

import {
  API_PUT_RACES_INCIDENTS,
} from '../../../../api';
import {
  renderFormErrors,
} from '../../../Shared/helpers';

/* global appData */

function FormSave({
  selectedRaceIncident,
}) {
  const [type, setType] = useState(selectedRaceIncident?.type);
  const [description, setDescription] = useState(selectedRaceIncident?.description);
  const [flag, setFlag] = useState(selectedRaceIncident?.flag);
  const [lap, setLap] = useState(selectedRaceIncident?.lap);
  const [lapSector, setLapSector] = useState(selectedRaceIncident?.lap_sector);
  const [lapLocation, setLapLocation] = useState(selectedRaceIncident?.lap_location);
  const [timeDuration, setTimeDuration] = useState(selectedRaceIncident?.time_duration);
  const [timeOfDay, setTimeOfDay] = useState(selectedRaceIncident?.time_of_day);

  const [formErrors, setFormErrors] = useState(null);
  const [formSubmitting, setFormSubmitting] = useState(false);

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      const url = API_PUT_RACES_INCIDENTS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceIncidentId}', selectedRaceIncident.id)
      ;

      await axios.put(url, qs.stringify({
        type,
        description,
        flag,
        lap,
        lapSector,
        lapLocation,
        timeDuration,
        timeOfDay,
      }));

      setFormErrors(null);

      toast.success('You have successfully edited the incident.');
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
      <h3>Type</h3>
      <div className="row">
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Type</Form.Label>
            <Form.Control
              as="select"
              value={type ?? ''}
              onChange={(event) => { setType(event.target.value) }}
              isInvalid={!!formErrors?.['type']}
            >
              <option value="">-- none --</option>
              {Object.keys(appData.race_incident_types).map((key) => {
                const label = appData.race_incident_types[key];

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
            {renderFormErrors(formErrors?.['type'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Flag</Form.Label>
            <Form.Control
              as="select"
              value={flag ?? ''}
              onChange={(event) => { setFlag(event.target.value) }}
              isInvalid={!!formErrors?.['flag']}
            >
              <option value="">-- none --</option>
              {Object.keys(appData.race_flags).map((key) => {
                const label = appData.race_flags[key];

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
            {renderFormErrors(formErrors?.['flag'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Description</Form.Label>
            <Form.Control
              type="textarea"
              value={description ?? ''}
              onChange={(event) => { setDescription(event.target.value) }}
              isInvalid={!!formErrors?.['description']}
            />
            {renderFormErrors(formErrors?.['description'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Lap</Form.Label>
            <Form.Control
              type="number"
              value={lap ?? ''}
              onChange={(event) => { setLap(event.target.value) }}
              isInvalid={!!formErrors?.['lap']}
            />
            {renderFormErrors(formErrors?.['lap'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Lap Sector</Form.Label>
            <Form.Control
              type="number"
              value={lapSector ?? ''}
              onChange={(event) => { setLapSector(event.target.value) }}
              isInvalid={!!formErrors?.['lapSector']}
            />
            {renderFormErrors(formErrors?.['lapSector'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Lap Location</Form.Label>
            <Form.Control
              type="number"
              step="0.001"
              min="0"
              max="1"
              value={lapLocation ?? ''}
              onChange={(event) => { setLapLocation(event.target.value) }}
              isInvalid={!!formErrors?.['lapLocation']}
            />
            <Form.Text muted>
              Where on the track did the incident happen (from 0 to 1, relative on the lap)?
            </Form.Text>
            {renderFormErrors(formErrors?.['lapLocation'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Time</Form.Label>
            <Form.Control
              value={timeDuration ?? ''}
              onChange={(event) => { setTimeDuration(event.target.value) }}
              isInvalid={!!formErrors?.['timeDuration']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(formErrors?.['timeDuration'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Time of day</Form.Label>
            <Form.Control
              type="time"
              step="1"
              value={timeOfDay ?? ''}
              onChange={(event) => { setTimeOfDay(event.target.value) }}
              isInvalid={!!formErrors?.['timeOfDay']}
            />
            {renderFormErrors(formErrors?.['timeOfDay'])}
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

FormSave.propTypes = {
  selectedRaceIncident: PropTypes.object,
};

export default FormSave;
