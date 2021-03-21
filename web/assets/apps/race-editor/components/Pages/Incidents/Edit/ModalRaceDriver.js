import React from 'react';
import {
  useStore,
  useSelector,
} from 'react-redux';
import {
  Modal,
  Button,
}  from 'react-bootstrap';

import {
  setModalOpen,
  selectModalOpen,
} from '../../../../store/selectedRaceIncidentRaceDriverSlice';

function ModalRaceDriver() {
  const store = useStore();
  const show = useSelector(selectModalOpen);

  const onHide = () => {
    store.dispatch(setModalOpen(false));
  };

  const onSaveButtonClick = () => {
    // TODO

    store.dispatch(setModalOpen(false));
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
        TODO
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
          onClick={onSaveButtonClick}
        >
          Save
        </Button>
      </Modal.Footer>
    </Modal>
  );
}

export default ModalRaceDriver;
