import React from 'react';

import {
  useRaceDriversListFetch,
} from '../hooks';
import DriversTable from './DriversTable';

/* global appData */

function DriversTableWrapper() {
  const {
    loaded,
    loading,
    data,
    error,
  } = useRaceDriversListFetch({
    slug: appData.race.slug,
  });

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
