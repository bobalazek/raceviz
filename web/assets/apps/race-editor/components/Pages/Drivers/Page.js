import React from 'react';
import {
  useSelector
} from 'react-redux';

import OverviewSection from './Overview/Section';
import EditSection from './Edit/Section';
import {
  selectSelectedRaceDriver,
} from '../../../store/appSlice';

function Page() {
  const selectedRaceDriver = useSelector(selectSelectedRaceDriver);

  return (
    <>
      {!!selectedRaceDriver && (
        <EditSection />
      )}
      {!selectedRaceDriver && (
        <OverviewSection />
      )}
    </>
  );
}

export default Page;
