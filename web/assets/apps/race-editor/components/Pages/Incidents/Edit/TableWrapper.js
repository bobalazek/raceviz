import React, {
  useState,
  useEffect,
} from 'react';
import PropTypes from 'prop-types';

import IncidentsService from '../../../../api/IncidentsService';
import Table from './Table';

function TableWrapper({
  selectedRaceIncident,
}) {
  const [loading, setLoading] = useState(false);
  const [loaded, setLoaded] = useState(null);
  const [data, setData] = useState([]);
  const [error, setError] = useState(null);

  const loadLaps = async () => {
    setLoading(true);

    try {
      const raceDrivers = await IncidentsService.loadRaceDrivers({
        raceIncident: selectedRaceIncident,
      });

      setData(raceDrivers);
    } catch (error) {
      setError(error.response.data.detail);
    } finally {
      setLoading(false);
      setLoaded(true);
    }
  };

  useEffect(loadLaps, [selectedRaceIncident]);

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
          No Incidents found yet.
        </div>
      )}
      {loaded && data.length > 0 && (
        <Table data={data} />
      )}
    </div>
  );
}

TableWrapper.propTypes = {
  selectedRaceIncident: PropTypes.object,
};

export default TableWrapper;
