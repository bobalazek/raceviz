import React from 'react';
import {
  useSelector,
} from 'react-redux';

import {
  selectLoaded,
  selectLoading,
  selectData,
  selectError,
} from '../store/driversSlice';
import DriversTable from './DriversTable';

function DriversTableWrapper() {
  const loaded = useSelector(selectLoaded);
  const loading = useSelector(selectLoading);
  const data = useSelector(selectData);
  const error = useSelector(selectError);

  return (
    <div>
      <h2>Drivers</h2>
      {loading && (
        <div className="text-center">
        <i className="fas fa-4x fa-spinner fa-spin"></i>
      </div>
      )}
      {error && (
        <div className="alert alert-danger">
          {error}
        </div>
      )}
      {loaded && data.length === 0 && (
        <div className="alert alert-info">
          No Drivers found yet.
        </div>
      )}
      {loaded && data.length > 0 && (
        <DriversTable data={data} />
      )}
    </div>
  );
}

export default DriversTableWrapper;
