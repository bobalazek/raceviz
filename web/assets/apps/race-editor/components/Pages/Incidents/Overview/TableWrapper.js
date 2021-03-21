import React from 'react';
import {
  useSelector,
} from 'react-redux';

import {
  selectLoaded,
  selectLoading,
  selectData,
  selectError,
} from '../../../../store/incidentsSlice';
import Table from './Table';

function TableWrapper() {
  const loaded = useSelector(selectLoaded);
  const loading = useSelector(selectLoading);
  const data = useSelector(selectData);
  const error = useSelector(selectError);

  return (
    <div>
      <h2>
        <span>Incidents </span>
        <small>({data.length})</small>
      </h2>
      {loading && (
        <div className="p-4 text-center">
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
          No Incidents found yet.
        </div>
      )}
      {loaded && data.length > 0 && (
        <Table data={data} />
      )}
    </div>
  );
}

export default TableWrapper;
