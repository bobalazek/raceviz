import React from 'react';
import {
  useStore,
} from 'react-redux';
import {
  Button,
} from 'react-bootstrap';
import PropTypes from 'prop-types';

import {
  DriversService,
} from '../../../../api';
import {
  setSelectedRaceDriver,
} from '../../../../store/appSlice';
import confirm from '../../../Shared/ConfirmDialog';

function Table({
  data,
}) {
  const store = useStore();

  const onEditButtonClick = (raceDriver, event) => {
    event.preventDefault();

    store.dispatch(setSelectedRaceDriver(raceDriver));
  };

  const onDeleteButtonClick = async (raceDriver, event) => {
    event.preventDefault();

    if (await confirm(
      'Are you sure you want to remove the driver?'
    )) {
      await DriversService.delete({
        raceDriver,
      })
    }
  };

  return (
    <div className="table-responsive">
      <table className="table">
        <thead>
          <tr>
            <th>Driver</th>
            <th>Team</th>
            <th>Laps</th>
            <th>Pit Stops</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {data.map((entry) => {
            return (
              <tr key={entry.id}>
                <td>{entry.season_driver.driver.name}</td>
                <td>{entry.season_driver.team.name}</td>
                <td>{entry.race_laps_count}</td>
                <td>{entry.race_pit_stops_count}</td>
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
