import React, {
  useState,
} from 'react';
import {
  useSelector
} from 'react-redux';
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
  selectData,
} from '../../../store/driversListSlice';
import {
  API_POST_RACES_DRIVERS,
} from '../../../api';
import {
  useSeasonsDriversFetch,
  useSeasonsTeamsFetch,
} from '../../../hooks';
import {
  renderFormErrors,
} from '../../Shared/helpers';

/* global appData */

function NewDriverForm() {
  const {
    data: seasonDrivers,
  } = useSeasonsDriversFetch();
  const {
    data: seasonTeams,
  } = useSeasonsTeamsFetch();

  const [driverId, setDriverId] = useState(0);
  const [teamId, setTeamId] = useState(0);

  const [formSubmitting, setFormSubmitting] = useState(false);
  const [formErrors, setFormErrors] = useState(null);

  const raceDrivers = useSelector(selectData);
  const addedDriverIds = raceDrivers.map((entry) => {
    return entry.driver.id;
  });

  const onDriverChange = (event) => {
    const value = parseInt(event.target.value);
    setDriverId(value);
    setFormErrors(null);

    const seasonDriver = seasonDrivers.find((entry) => {
      return entry.driver.id === value;
    });

    const seasonDriverTeam = seasonTeams.find((entry) => {
      return entry.team.id === seasonDriver.team.id;
    });

    if (!seasonDriverTeam) {
      return;
    }

    setTeamId(seasonDriverTeam.team.id);
  };

  const onTeamChange = (event) => {
    const value = parseInt(event.target.value);
    setTeamId(value);
    setFormErrors(null);
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      const url = API_POST_RACES_DRIVERS
        .replace('{raceSlug}', appData.race.slug)
      ;

      const response = await axios.post(url, qs.stringify({
          driver: driverId,
          team: teamId,
      }));

      if (response.data.errors) {
        setFormErrors(response.data.errors);

        toast.error('Please fix the errors first!');
        return;
      }

      toast.success('You have successfully added the driver.');

      setDriverId(0);
      setTeamId(0);

      window.dispatchEvent(new CustomEvent('driver-editor:new-driver'));
    } catch(error) {
      toast.error(error.response.data.detail);
    } finally {
      setFormSubmitting(false);
    }
  };

  return (
    <Form noValidate onSubmit={onSubmit}>
      <div className="row">
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Driver</Form.Label>
            <Form.Control
              as="select"
              value={driverId}
              onChange={onDriverChange}
              isInvalid={!!formErrors?.['driver']}
            >
              <option value="0">-- none --</option>
              {seasonDrivers.map((entry) => {
                const alreadyAddedDriver = addedDriverIds.includes(entry.driver.id);
                return (
                  <option
                    key={entry.driver.id}
                    value={entry.driver.id}
                    disabled={alreadyAddedDriver}
                  >
                    {entry.driver.name}
                    {alreadyAddedDriver && ' (already added)'}
                  </option>
                );
              })}
            </Form.Control>
            {renderFormErrors(formErrors?.['driver'])}
          </Form.Group>
        </div>
        <div className="col-md-6">
          <Form.Group>
            <Form.Label>Team</Form.Label>
            <Form.Control
              as="select"
              value={teamId}
              onChange={onTeamChange}
              isInvalid={!!formErrors?.['team']}
            >
              <option value="0">-- none --</option>
              {seasonTeams.map((entry) => {
                return (
                  <option
                    key={entry.team.id}
                    value={entry.team.id}
                  >
                    {entry.team.name}
                  </option>
                );
              })}
            </Form.Control>
            {renderFormErrors(formErrors?.['team'])}
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
        Add new Race Driver
      </Button>
    </Form>
  );
}

export default NewDriverForm;
