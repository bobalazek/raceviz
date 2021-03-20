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
} from '../../../hooks';
import {
  renderFormErrors,
} from '../../Shared/helpers';

/* global appData */

function NewDriverForm() {
  const {
    data: seasonDrivers,
  } = useSeasonsDriversFetch();

  const [seasonDriverId, setSeasonDriverId] = useState(0);

  const [formSubmitting, setFormSubmitting] = useState(false);
  const [formErrors, setFormErrors] = useState(null);

  const raceDrivers = useSelector(selectData);
  const addedSeasonDriverIds = raceDrivers.map((entry) => {
    return entry.id;
  });

  const onSeasonDriverChange = (event) => {
    const value = parseInt(event.target.value);

    setSeasonDriverId(value);
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

      await axios.post(url, qs.stringify({
        seasonDriver: seasonDriverId,
      }));

      toast.success('You have successfully added the driver.');

      setSeasonDriverId(0);

      window.dispatchEvent(new CustomEvent('driver-editor:new-driver'));
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
      <Form.Group>
        <Form.Label>Season Driver</Form.Label>
        <Form.Control
          as="select"
          value={seasonDriverId}
          onChange={onSeasonDriverChange}
          isInvalid={!!formErrors?.['seasonDriver']}
        >
          <option value="0">-- none --</option>
          {seasonDrivers.map((entry) => {
            const alreadyAddedSeasonDriver = addedSeasonDriverIds.includes(entry.id);
            return (
              <option
                key={entry.id}
                value={entry.id}
                disabled={alreadyAddedSeasonDriver}
              >
                {entry.driver.name}
                {' '}
                ({entry.team.name})
                {entry.temporary && ' (temporary)'}
                {alreadyAddedSeasonDriver && ' (already added)'}
              </option>
            );
          })}
        </Form.Control>
        {renderFormErrors(formErrors?.['seasonDriver'])}
      </Form.Group>
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
