import React from 'react';
import PropTypes from 'prop-types';

function DriversTable({
  data,
}) {
  const onEditButtonClick = () => {
    // TODO
  };

  const onDeleteButtonClick = () => {
    // TODO
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
                    <a href="#" className="btn btn-sm btn-primary" onClick={onEditButtonClick}>Edit</a>
                    <a href="#" className="btn btn-sm btn-danger" onClick={onDeleteButtonClick}>Delete</a>
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
