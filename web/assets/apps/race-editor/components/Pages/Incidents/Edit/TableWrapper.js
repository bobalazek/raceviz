import React, {
  useEffect,
} from 'react';
import {
  useSelector,
} from 'react-redux';

import {
  selectData as selectRaceIncidentData,
} from '../../../../store/selectedRaceIncidentSlice';
import {
  selectLoading,
  selectLoaded,
  selectData,
  selectError,
} from '../../../../store/incidentRaceDriversSlice';
import IncidentsService from '../../../../api/IncidentsService';
import Table from './Table';

function TableWrapper() {
  const selectedRaceIncident = useSelector(selectRaceIncidentData);

  const loading = useSelector(selectLoading);
  const loaded = useSelector(selectLoaded);
  const data = useSelector(selectData);
  const error = useSelector(selectError);

  useEffect(async () => {
    await IncidentsService.loadRaceDrivers({
      raceIncident: selectedRaceIncident,
    });
  }, [selectedRaceIncident])

  return (
    <div>
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
          No involved race drivers found yet.
        </div>
      )}
      {loaded && data.length > 0 && (
        <Table data={data} />
      )}
    </div>
  );
}

export default TableWrapper;
