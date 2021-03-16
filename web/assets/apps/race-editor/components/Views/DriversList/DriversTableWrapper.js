import React from 'react';
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

function DriversTableWrapper() {
  const loaded = useSelector(selectLoaded);
  const loading = useSelector(selectLoading);
  const data = useSelector(selectData);
  const error = useSelector(selectError);

  const onPrepareAllDriversButtonClick = async () => {
    const confirmation = await confirm(
      'Are you sure you want to do this? This will add all all the (permanent) drivers to this race.'
    );
    if (!confirmation) {
      return;
    }

    await DriversService.prepareAll();
  };

  return (
    <div>
      <h2 className="d-flex">
        <div className="mr-auto">
          <span>Drivers </span>
          <small>({data.length})</small>
        </div>
        <div>
          <Button
            variant="primary"
            onClick={onPrepareAllDriversButtonClick}
          >
            Prepare All Drivers
          </Button>
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
