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

function Table({
  data,
}) {
  const store = useStore();

  const onEditButtonClick = (raceIncident, event) => {
    event.preventDefault();

    store.dispatch(setData(raceIncident));
  };

  const onDeleteButtonClick = async (raceIncidentRaceDriver, event) => {
    event.preventDefault();

    if (await confirm(
      'Are you sure you want to remove the incident race driver?'
    )) {
      await IncidentsService.deleteRaceDriver({
        raceIncidentRaceDriver,
      });
    }
  };

  return (
    <div className="table-responsive">
      <table className="table">
        <thead>
          <tr>
            <th>Race Driver</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {data.map((entry) => {
            return (
              <tr key={entry.id}>
                <td>{entry.season_driver.driver.name}</td>
                <td>{entry.description}</td>
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
