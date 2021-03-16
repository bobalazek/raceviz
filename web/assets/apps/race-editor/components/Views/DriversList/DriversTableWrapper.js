import React, {
  useState,
} from 'react';
import {
  useSelector,
} from 'react-redux';
import {
  Button,
} from 'react-bootstrap';

import {
  selectLoaded,
  selectLoading,
  selectData,
  selectError,
} from '../../../store/driversListSlice';
import {
  DriversService,
} from '../../../api.js';
import DriversTable from './DriversTable';
import confirm from '../../Shared/ConfirmDialog';

/* global appData */

function DriversTableWrapper() {
  const loaded = useSelector(selectLoaded);
  const loading = useSelector(selectLoading);
  const data = useSelector(selectData);
  const error = useSelector(selectError);
  const [prepareAllDriversButtonSubmitting, setPrepareAllDriversButtonSubmitting] = useState(false);
  const [prepareAllDriverLapsButtonSubmitting, setPrepareAllDriverLapsButtonSubmitting] = useState(false);

  const onPrepareAllDriversButtonClick = async () => {
    setPrepareAllDriversButtonSubmitting(true);

    const confirmation = await confirm(
      'Are you sure you want to do this? This will add all the (permanent) drivers to this race.'
    );
    if (!confirmation) {
      setPrepareAllDriversButtonSubmitting(false);

      return;
    }

    await DriversService.prepareAll();

    setPrepareAllDriversButtonSubmitting(false);
  };

  const onPrepareAllDriverLapsButtonClick = async () => {
    setPrepareAllDriverLapsButtonSubmitting(true);

    const confirmation = await confirm(
      'Are you sure you want to do this? This will override all the existing lap data you set for any of the Race Drivers.'
    );
    if (!confirmation) {
      setPrepareAllDriverLapsButtonSubmitting(false);

      return;
    }

    await DriversService.prepareLapsFromErgastAll();

    setPrepareAllDriverLapsButtonSubmitting(false);
  };

  return (
    <div>
      <h2 className="d-flex">
        <div className="mr-auto">
          <span>Drivers </span>
          <small>({data.length})</small>
        </div>
        <div>
          <div className="btn-group">
            <Button
              variant="info"
              onClick={onPrepareAllDriversButtonClick}
              disabled={prepareAllDriversButtonSubmitting}
            >
              {prepareAllDriversButtonSubmitting && (
                <span><i className="fas fa-spinner fa-spin"></i> </span>
              )}
              Prepare All Drivers
            </Button>
            {appData.race.ergast_series_season_and_round && (
              <Button
                variant="danger"
                onClick={onPrepareAllDriverLapsButtonClick}
                disabled={prepareAllDriverLapsButtonSubmitting}
              >
                {prepareAllDriverLapsButtonSubmitting && (
                  <span><i className="fas fa-spinner fa-spin"></i> </span>
                )}
                Prepare All Driver Laps (from Ergast)
              </Button>
            )}
          </div>
        </div>
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
