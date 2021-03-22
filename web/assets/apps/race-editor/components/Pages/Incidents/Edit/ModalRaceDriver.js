import React, {
  useEffect,
  useState,
} from 'react';
import {
  useStore,
  useSelector,
} from 'react-redux';
import {
  Modal,
  Button,
  Form,
}  from 'react-bootstrap';
import {
  toast,
} from 'react-toastify';

import {
  selectData,
} from '../../../../store/selectedRaceIncidentSlice';
import {
  setModalOpen,
  selectModalOpen,
  selectData as selectRaceDriverData,
} from '../../../../store/selectedRaceIncidentRaceDriverSlice';
import {
  useRaceDriversFetch,
} from '../../../../hooks';
import {
  renderFormErrors,
} from '../../../Shared/helpers';
import IncidentsService from '../../../../api/IncidentsService';

function ModalRaceDriver() {
  const store = useStore();
  const show = useSelector(selectModalOpen);
  const selectedRaceIncident = useSelector(selectData);
  const selectedRaceIncidentRaceDriver = useSelector(selectRaceDriverData);

  // TODO: disable already added race drivers below in the select!

  const {
    data: raceDrivers,
  } = useRaceDriversFetch();

  const [raceDriverId, setRaceDriverId] = useState(0);

  const [formSubmitting, setFormSubmitting] = useState(false);
  const [formErrors, setFormErrors] = useState(null);

  useEffect(() => {
    setRaceDriverId(selectedRaceIncidentRaceDriver?.race_driver?.id ?? 0);
    setFormErrors(null);
  }, [selectedRaceIncidentRaceDriver])

  const onRaceDriverChange = (event) => {
    const value = parseInt(event.target.value);

    setRaceDriverId(value);
    setFormErrors(null);
  };

  const onHide = () => {
    store.dispatch(setModalOpen(false));
  };

  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    setFormSubmitting(true);

    const formData = {
      raceDriver: raceDriverId,
    };

    try {
      await IncidentsService.saveRaceDriver({
        raceIncident: selectedRaceIncident,
        raceIncidentRaceDriver: selectedRaceIncidentRaceDriver,
        formData,
      });

      setRaceDriverId(0);

      store.dispatch(setModalOpen(false));

      toast.success('You have successfully added the driver.');

      await IncidentsService.loadRaceDrivers({
        raceIncident: selectedRaceIncident,
      });
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
    <Modal
      show={show}
      onHide={onHide}
      backdrop="static"
      keyboard={false}
      size="lg"
    >
      <Modal.Header closeButton>
        <Modal.Title>Involved Race Driver</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Form noValidate onSubmit={onSubmit}>
          <Form.Group>
            <Form.Label>Race Driver</Form.Label>
            <Form.Control
              as="select"
              value={raceDriverId}
              onChange={onRaceDriverChange}
              isInvalid={!!formErrors?.['raceDriver']}
            >
              <option value="0">-- none --</option>
              {raceDrivers.map((entry) => {
                return (
                  <option
                    key={entry.id}
                    value={entry.id}
                  >
                    {entry.season_driver.driver.name}
                    {' '}
                    ({entry.season_driver.team.name})
                  </option>
                );
              })}
            </Form.Control>
            {renderFormErrors(formErrors?.['raceDriver'])}
          </Form.Group>
          {renderFormErrors(formErrors?.['*'], true)}
        </Form>
      </Modal.Body>
      <Modal.Footer>
        <Button
          variant="secondary"
          onClick={onHide}
        >
          Close
        </Button>
        <Button
          variant="primary"
          onClick={onSubmit}
          disabled={formSubmitting}
        >
          Save
        </Button>
      </Modal.Footer>
    </Modal>
  );
}

export default ModalRaceDriver;
