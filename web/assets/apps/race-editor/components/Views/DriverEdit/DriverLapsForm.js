import React, {
  useEffect,
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
  DriversService,
  API_PUT_RACES_DRIVERS_LAPS,
} from '../../../api';
import {
  renderFormErrors,
} from '../../Shared/helpers';
import DriverLapsFormRow from './DriverLapsFormRow';

/* global appData */

function DriverLapsForm({
  selectedRaceDriver,
}) {
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [formData, setFormData] = useState([]);
  const [formErrors, setFormErrors] = useState(null);
  const [formSubmitting, setFormSubmitting] = useState(false);

  const loadLaps = async () => {
    setLoading(true);

    try {
      const laps = await DriversService.loadLaps({
        raceDriver: selectedRaceDriver,
      });

      setFormData(laps);
    } catch (error) {
      setError(error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(loadLaps, [selectedRaceDriver]);

  const setFormLapData = (index, type, field, value) => {
    const newFormData = JSON.parse(JSON.stringify(formData));

    newFormData[index][type][field] = value;

    setFormData(newFormData);
  };

  const setFormHadPitStopData = (index) => {
    const newFormData = JSON.parse(JSON.stringify(formData));

    newFormData[index]['had_race_pit_stop'] = !newFormData[index]['had_race_pit_stop'];

    setFormData(newFormData);
  };

  const onAddNewClick = () => {
    const newFormData = JSON.parse(JSON.stringify(formData));
    const lap = newFormData.length + 1;

    newFormData.push({
      lap,
      had_race_pit_stop: false,
      race_lap: {
        id: null,
        lap,
        position: null,
        time: null,
        time_of_day: null,
        tyres: null,
      },
      race_pit_stop: {
        id: null,
        lap,
        time: null,
        time_of_day: null,
      },
    });

    setFormData(newFormData);
  };

  const onRemoveLastClick = () => {
    const newFormData = JSON.parse(JSON.stringify(formData));

    newFormData.pop();

    setFormData(newFormData);
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      const url = API_PUT_RACES_DRIVERS_LAPS
        .replace('{raceSlug}', appData.race.slug)
        .replace('{raceDriverId}', selectedRaceDriver.id)
      ;

      const response = await axios.put(url, qs.stringify(formData));

      if (response.data.errors) {
        setFormErrors(response.data.errors);

        toast.error('Please fix the errors first!');
        return;
      }

      toast.success('You have successfully edited the driver laps.');

      setFormErrors(null);

      loadLaps();
    } catch(error) {
      toast.error(error.response.data.detail);
    } finally {
      setFormSubmitting(false);
    }
  };

  const canAddNewLap = formData.length < appData.race.laps;
  const canRemoveLastLap = formData.length > 0;

  if (loading) {
    return (
      <div className="p-4 text-center">
        <i className="fas fa-4x fa-spinner fa-spin"></i>
      </div>
    );
  }

  if (error) {
    return (
      <div className="alert alert-danger">
        {error}
      </div>
    );
  }

  return (
    <Form noValidate onSubmit={onSubmit}>
      {formData.map((entry, index) => {
        const entryErrors = formErrors?.[index];

        return (
          <DriverLapsFormRow
            key={index}
            index={index}
            entry={entry}
            entryErrors={entryErrors}
            setFormLapData={setFormLapData}
            setFormHadPitStopData={setFormHadPitStopData}
          />
        );
      })}
      <div className="my-3">
        {canAddNewLap && (
          <Button
            variant="primary"
            className="mr-3"
            onClick={onAddNewClick}
          >
            Add new lap
          </Button>
        )}
        {canRemoveLastLap && (
          <Button
            variant="primary"
            onClick={onRemoveLastClick}
          >
            Remove last lap
          </Button>
        )}
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

DriverLapsForm.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default DriverLapsForm;
