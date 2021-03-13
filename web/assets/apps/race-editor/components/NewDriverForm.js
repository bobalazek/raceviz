import React, {
  useState,
} from 'react';
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
  API_POST_RACES_DRIVERS,
} from '../api';
import {
  useSeasonsDriversFetch,
  useSeasonsTeamsFetch,
} from '../hooks';

/* global appData */

function NewDriverForm() {
  const {
    data: seasonDrivers,
  } = useSeasonsDriversFetch({
    slug: appData.race.season.slug,
  });
  const {
    data: seasonTeams,
  } = useSeasonsTeamsFetch({
    slug: appData.race.season.slug,
  });

  const [driverId, setDriverId] = useState(0);
  const [teamId, setTeamId] = useState(0);
  const [formSubmitting, setFormSubmitting] = useState(false);
  const [formErrors, setFormErrors] = useState(null);

  const onDriverChange = (event) => {
    setDriverId(event.target.value);
    setFormErrors(null);
  };

  const onTeamChange = (event) => {
    setTeamId(event.target.value);
    setFormErrors(null);
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    try {
      const url = API_POST_RACES_DRIVERS
        .replace('{slug}', appData.race.slug)
      ;

      const response = await axios.post(url, qs.stringify({
        driver_id: driverId,
        team_id: teamId,
      }));

      if (response.data.errors) {
        setFormErrors(response.data.errors);

        toast.error('Please fix the errors first!');
        return;
      }

      toast.success('You have successfully added the driver.');
    } catch(error) {
      toast.error(error.response.data.detail);
    } finally {
      setFormSubmitting(false);
    }
  };

  const renderFormErrors = (errors, isAlert = false) => {
    if (!errors) {
      return;
    }

    if (isAlert) {
      return (
        <>
          {errors.map((error, index) => {
            return (
              <div
                key={index}
                className="alert alert-danger"
              >
                {error}
              </div>
            );
          })}
        </>
      );
    }

    return (
      <>
        {errors.map((error, index) => {
          return (
            <Form.Control.Feedback
              key={index}
              type="invalid"
            >
              {error}
            </Form.Control.Feedback>
          );
        })}
      </>
    );
  };

  return (
    <div>
      <h3>New Driver</h3>
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
                  return (
                    <option
                      key={entry.driver.id}
                      value={entry.driver.id}
                    >
                      {entry.driver.name}
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
          variant="primary"
          type="submit"
          disabled={formSubmitting}
        >
          Submit
        </Button>
      </Form>
    </div>
  );
}

export default NewDriverForm;
