import React from 'react';
import {
  useSelector
} from 'react-redux';

import OverviewSection from './Overview/Section';
import EditSection from './Edit/Section';
import {
  selectData,
} from '../../../store/selectedRaceDriverSlice';

function Page() {
  const selectedRaceDriver = useSelector(selectData);

  return (
    <>
      {!!selectedRaceDriver && (
        <EditSection selectedRaceDriver={selectedRaceDriver} />
      )}
      {!selectedRaceDriver && (
        <OverviewSection selectedRaceDriver={selectedRaceDriver} />
      )}
    </>
  );
}

export default Page;
