import React from 'react';
import {
  useStore,
} from 'react-redux';
import PropTypes from 'prop-types';

import {
  DriversService,
} from '../../../api';
import {
  setSelectedRaceDriver,
} from '../../../store/appSlice';
import confirm from '../../Shared/ConfirmDialog';
import { Button } from 'react-bootstrap';

function DriversTable({
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
      DriversService.delete({
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {data.map((entry) => {
            return (
              <tr key={entry.id}>
                <td>{entry.driver.name}</td>
                <td>{entry.team.name}</td>
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

DriversTable.propTypes = {
  data: PropTypes.array,
};

export default DriversTable;