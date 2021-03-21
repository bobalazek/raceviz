import React, {
  useEffect,
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
import confirm from '../../../Shared/ConfirmDialog';
import DriverLapsFormRow from './FormLapsRow';

/* global appData */

function FormLaps({
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
      setError(error.response.data.detail);
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

  const onPullFromErgastClick = async () => {
    const confirmation = await confirm(
      'Are you sure you want to do this? This will overwite all your current data (but not save yet)!'
    );
    if (!confirmation) {
      return;
    }

    try {
      const ergastLaps = await DriversService.loadLapsFromErgast({
        raceDriver: selectedRaceDriver,
      });

      const newFormData = [];

      if (ergastLaps.length > 0) {
        for (let i = 0; i < ergastLaps.length; i++) {
          const entry = ergastLaps[i];
          const lap = entry.lap;

          newFormData.push({
            lap,
            race_lap: {
              id: null,
              lap,
              position: entry.race_lap.position,
              time: entry.race_lap.time,
              time_of_day: null,
              tyres: null,
            },
            race_pit_stop: {
              id: null,
              lap,
              time: entry.race_pit_stop?.time ?? null,
              time_of_day: entry.race_pit_stop?.time_of_day ?? null,
            },
            had_race_pit_stop: !!entry.race_pit_stop,
          });
        }
      } else {
        toast.info('No laps data found for this driver on this race.');
      }

      setFormData(newFormData);
    } catch (error) {
      toast.error(error.response.data.detail);
    }
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      await DriversService.saveLaps({
        raceDriver: selectedRaceDriver,
        formData,
      });

      toast.success('You have successfully edited the driver laps.');

      setFormErrors(null);

      loadLaps();
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
            className="mr-3"
            onClick={onRemoveLastClick}
          >
            Remove last lap
          </Button>
        )}
        {appData.race.ergast_series_season_and_round && (
          <Button
            variant="danger"
            onClick={onPullFromErgastClick}
          >
            Pull from ergast
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

FormLaps.propTypes = {
  selectedRaceDriver: PropTypes.object,
};

export default FormLaps;
