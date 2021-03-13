import React from 'react';
import PropTypes from 'prop-types';

import {
  DriversService,
} from '../api';
import confirm from './ConfirmDialog';

/* global appData */

function DriversTable({
  data,
}) {
  const onEditButtonClick = (raceDriver, event) => {
    event.preventDefault();

    // TODO
  };

  const onDeleteButtonClick = async (raceDriver, event) => {
    event.preventDefault();

    if (await confirm(
      'Are you sure you want to remove the driver?'
    )) {
      DriversService.delete({
        slug: appData.race.slug,
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
                    <a href="#" className="btn btn-sm btn-primary" onClick={onEditButtonClick.bind(this, entry)}>Edit</a>
                    <a href="#" className="btn btn-sm btn-danger" onClick={onDeleteButtonClick.bind(this, entry)}>Delete</a>
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
