import React from 'react';
import PropTypes from 'prop-types';
import {
  confirmable,
  createConfirmation,
} from 'react-confirm';
import {
  Modal,
  Button,
} from 'react-bootstrap';

const ConfirmDialog = ({
  show,
  proceed,
  confirmation,
  options,
}) => {
  return (
    <Modal show={show} onHide={() => { proceed(false) }}>
      {options?.title && (
        <Modal.Header closeButton>
          {options.title}
        </Modal.Header>
      )}
      <Modal.Body>
        {confirmation}
      </Modal.Body>
      <Modal.Footer>
        <Button
          variant="default"
          onClick={() => { proceed(false) }}
        >
          Close
        </Button>
        <Button
          autoFocus
          variant="primary"
          onClick={() => { proceed(true) }}
        >
          Confirm
        </Button>
      </Modal.Footer>
    </Modal>
  );
}

ConfirmDialog.propTypes = {
  show: PropTypes.bool,
  proceed: PropTypes.func,
  confirmation: PropTypes.string,
  options: PropTypes.object,
};

const confirm = createConfirmation(confirmable(ConfirmDialog));

export default (confirmation, options = {}) => {
  return confirm({ confirmation, options });
}
