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

      loadLaps();
    } catch(error) {
      toast.error(error.response.data.detail);
    } finally {
      setFormSubmitting(false);
    }
  };

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
