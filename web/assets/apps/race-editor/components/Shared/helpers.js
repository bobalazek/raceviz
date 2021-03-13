import React from 'react';
import {
  Form,
} from 'react-bootstrap';

export const renderFormErrors = (errors, isAlert = false) => {
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
