import React from 'react';
import {
  useSelector
} from 'react-redux';

import OverviewSection from './Overview/Section';
import EditSection from './Edit/Section';
import {
  selectData,
} from '../../../store/selectedRaceIncidentSlice';

function Page() {
  const selectedRaceIncident = useSelector(selectData);

  return (
    <>
      {!!selectedRaceIncident && (
        <EditSection selectedRaceIncident={selectedRaceIncident} />
      )}
      {!selectedRaceIncident && (
        <OverviewSection selectedRaceIncident={selectedRaceIncident} />
      )}
    </>
  );
}

export default Page;
