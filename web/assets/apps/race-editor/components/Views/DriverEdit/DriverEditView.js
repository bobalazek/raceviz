import React, {
  useState,
} from 'react';
import {
  useStore,
  useSelector,
} from 'react-redux';
import {
  Nav,
  Button,
}  from 'react-bootstrap';

import {
  setSelectedRaceDriver,
  selectSelectedRaceDriver,
} from '../../../store/appSlice';
import EditDriverForm from './EditDriverForm';
import DriverLapsForm from './DriverLapsForm';

function DriverEditView() {
  const store = useStore();
  const selectedRaceDriver = useSelector(selectSelectedRaceDriver);
  const [tab, setTab] = useState('general');

  const onBackToListClick = () => {
    store.dispatch(setSelectedRaceDriver(null));
  };

  return (
    <>
      <h2>
        <span>{selectedRaceDriver.season_driver.driver.name} </span>
        <small>({selectedRaceDriver.season_driver.team.name}) </small>
        <Button
          size="sm"
          onClick={onBackToListClick}
        >
          Back to List
        </Button>
      </h2>
      <Nav
        fill
        variant="tabs"
        onSelect={(key) => { setTab(key) }}
        defaultActiveKey="general"
      >
        <Nav.Item>
          <Nav.Link eventKey="general">
            General
          </Nav.Link>
        </Nav.Item>
        <Nav.Item>
          <Nav.Link eventKey="laps">
            Laps
          </Nav.Link>
        </Nav.Item>
      </Nav>
      <div className="p-4 border-left border-right border-bottom">
        {tab === 'general' && <EditDriverForm selectedRaceDriver={selectedRaceDriver} />}
        {tab === 'laps' && <DriverLapsForm selectedRaceDriver={selectedRaceDriver} />}
      </div>
    </>
  );
}

export default DriverEditView;
