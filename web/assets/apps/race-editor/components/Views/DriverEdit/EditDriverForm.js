import React from 'react';
import {
  Form,
} from 'react-bootstrap';

function EditDriverForm() {
  const onSubmit = async (event) => {
    event.preventDefault();
    event.stopPropagation();

    // TODO
  };

  return (
    <Form noValidate onSubmit={onSubmit}>
      TODO
    </Form>
  );
}

export default EditDriverForm;
