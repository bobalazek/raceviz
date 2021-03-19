import React from 'react';
import PropTypes from 'prop-types';
import {
  Form,
} from 'react-bootstrap';

import {
  renderFormErrors,
} from '../../Shared/helpers';

/* global appData */

function DriverLapsFormRow({
  index,
  entry,
  entryErrors,
  setFormLapData,
  setFormHadPitStopData,
}) {
  return (
    <div key={entry.lap}>
      <h4>
        <span>Lap {entry.lap} </span>
        <Form.Group
          as="small"
          className="d-inline-block"
          controlId={'had-pit-stop-' + index}
        >
          <Form.Check
            custom
            inline
            type="checkbox"
            label="Had Pit Stop?"
            checked={entry?.['had_race_pit_stop']}
            onChange={() => { setFormHadPitStopData(index) }}
          />
        </Form.Group>
      </h4>
      <div className="row">
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Time</Form.Label>
            <Form.Control
              value={entry.race_lap?.['time_duration'] ?? ''}
              onChange={(event) => { setFormLapData(index, 'race_lap', 'timeDuration', event.target.value) }}
              isInvalid={!!entryErrors?.['race_lap']?.['timeDuration']}
            />
            <Form.Text muted>
              Enter a valid duration time (1:06:20.123 or 1:09.456 or 05.789).
            </Form.Text>
            {renderFormErrors(entryErrors?.['race_lap']?.['timeDuration'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Time of day</Form.Label>
            <Form.Control
              type="time"
              step="1"
              value={entry.race_lap?.['time_of_day'] ?? ''}
              onChange={(event) => { setFormLapData(index, 'race_lap', 'time_of_day', event.target.value) }}
              isInvalid={!!entryErrors?.['race_lap']?.['timeOfDay']}
            />
            {renderFormErrors(entryErrors?.['race_lap']?.['timeOfDay'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Position</Form.Label>
            <Form.Control
              type="number"
              value={entry.race_lap?.['position'] ?? ''}
              onChange={(event) => { setFormLapData(index, 'race_lap', 'position', event.target.value) }}
              isInvalid={!!entryErrors?.['race_lap']?.['position']}
            />
            {renderFormErrors(entryErrors?.['race_lap']?.['position'])}
          </Form.Group>
        </div>
        <div className="col-md-3">
          <Form.Group>
            <Form.Label>Tyres</Form.Label>
            <Form.Control
              as="select"
              value={entry.race_lap?.['tyres'] ?? ''}
              onChange={(event) => { setFormLapData(index, 'race_lap', 'tyres', event.target.value) }}
              isInvalid={!!entryErrors?.['race_lap']?.['tyres']}
            >
              <option value="">-- none --</option>
              {Object.keys(appData.tyres).map((key) => {
                const label = appData.tyres[key];

                return (
                  <option
                    key={key}
                    value={key}
                  >
                    {label}
                  </option>
                );
              })}
            </Form.Control>
            {renderFormErrors(entryErrors?.['race_lap']?.['tyres'])}
          </Form.Group>
        </div>
      </div>
      {renderFormErrors(entryErrors?.['race_lap']?.['*'], true)}
      {entry?.['had_race_pit_stop'] && (
        <>
          <h6>Pit Stop</h6>
          <div className="row">
            <div className="col-md-3">
              <Form.Group>
                <Form.Label>Time</Form.Label>
                <Form.Control
                  value={entry.race_pit_stop?.['time_duration'] ?? ''}
                  onChange={(event) => { setFormLapData(index, 'race_pit_stop', 'time_duration', event.target.value) }}
                  isInvalid={!!entryErrors?.['race_pit_stop']?.['timeDuration']}
                />
                <Form.Text muted>
                  Enter a valid duration time (02.789).
                </Form.Text>
                {renderFormErrors(entryErrors?.['race_pit_stop']?.['timeDuration'])}
              </Form.Group>
            </div>
            <div className="col-md-3">
              <Form.Group>
                <Form.Label>Time of day</Form.Label>
                <Form.Control
                  type="time"
                  step="1"
                  value={entry.race_pit_stop?.['time_of_day'] ?? ''}
                  onChange={(event) => { setFormLapData(index, 'race_pit_stop', 'time_of_day', event.target.value) }}
                  isInvalid={!!entryErrors?.['race_pit_stop']?.['timeOfDay']}
                />
                {renderFormErrors(entryErrors?.['race_pit_stop']?.['timeOfDay'])}
              </Form.Group>
            </div>
          </div>
          {renderFormErrors(entryErrors?.['race_pit_stop']?.['*'], true)}
        </>
      )}
    </div>
  )
}

DriverLapsFormRow.propTypes = {
  index: PropTypes.number,
  entry: PropTypes.object,
  entryErrors: PropTypes.object,
  setFormLapData: PropTypes.func,
  setFormHadPitStopData: PropTypes.func,
};

export default DriverLapsFormRow;
