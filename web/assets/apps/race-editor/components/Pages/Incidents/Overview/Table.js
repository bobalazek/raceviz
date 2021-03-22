import React from 'react';
import {
  useStore,
} from 'react-redux';
import {
  Button,
} from 'react-bootstrap';
import PropTypes from 'prop-types';

import {
  setData,
} from '../../../../store/selectedRaceIncidentSlice';
import IncidentsService from '../../../../api/IncidentsService';
import confirm from '../../../Shared/ConfirmDialog';

/* global appData */

function Table({
  data,
}) {
  const store = useStore();

  const onEditButtonClick = (raceIncident, event) => {
    event.preventDefault();

    store.dispatch(setData(raceIncident));
  };

  const onDeleteButtonClick = async (raceIncident, event) => {
    event.preventDefault();

    if (await confirm(
      'Are you sure you want to remove the incident?'
    )) {
      await IncidentsService.delete({
        raceIncident,
      });
    }
  };

  return (
    <div className="table-responsive">
      <table className="table">
        <thead>
          <tr>
            <th>Type</th>
            <th>Description</th>
            <th>Flag</th>
            <th>Safety Vehicle</th>
            <th>Lap</th>
            <th>Lap Sector</th>
            <th>Lap Location</th>
            <th>Time Duration</th>
            <th>Time Of Day</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {data.map((entry) => {
            return (
              <tr key={entry.id}>
                <td>{appData.race_incident_types[entry.type] ?? ''}</td>
                <td>{entry.description}</td>
                <td>{appData.race_flags[entry.flag] ?? ''}</td>
                <td>{appData.safety_vehicles[entry.safety_vehicle] ?? ''}</td>
                <td>{entry.lap}</td>
                <td>{entry.lap_sector}</td>
                <td>{entry.lap_location}</td>
                <td>{entry.time_duration}</td>
                <td>{entry.time_of_day}</td>
                <td>
                  <div className="btn-group">
                    <Button
                      size="sm"
                      variant="primary"
                      onClick={onEditButtonClick.bind(this, entry)}
                    >
                      Edit
                    </Button>
                    <Button
                      size="sm"
                      variant="danger"
                      onClick={onDeleteButtonClick.bind(this, entry)}
                    >
                      Delete
                    </Button>
                  </div>
                </td>
              </tr>
            );
          })}
        </tbody>
      </table>
    </div>
  );
}

Table.propTypes = {
  data: PropTypes.array,
};

export default Table;
