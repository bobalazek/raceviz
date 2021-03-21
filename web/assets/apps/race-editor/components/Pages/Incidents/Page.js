import React from 'react';
import {
  useSelector
} from 'react-redux';

import OverviewSection from './Overview/Section';
import {
  selectData,
} from '../../../store/selectedRaceIncidentSlice';

function Page() {
  const selectedRaceIncident = useSelector(selectData);

  return (
    <>
      {!selectedRaceIncident && (
        <OverviewSection selectedRaceIncident={selectedRaceIncident} />
      )}
    </>
  );
}

export default Page;
