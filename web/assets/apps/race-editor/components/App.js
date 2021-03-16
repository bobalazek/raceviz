import React, {
  useState,
} from 'react';
import {
  useSelector
} from 'react-redux';
import {
  Nav,
}  from 'react-bootstrap';

import GeneralView from './Views/General/GeneralView';
import DriversListView from './Views/DriversList/DriversListView';
import DriverEditView from './Views/DriverEdit/DriverEditView';
import {
  selectSelectedRaceDriver,
} from '../store/appSlice';

function App() {
  const selectedRaceDriver = useSelector(selectSelectedRaceDriver);
  const [tab, setTab] = useState('general');

  return (
    <>
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
          <Nav.Link eventKey="drivers">
            Drivers
          </Nav.Link>
        </Nav.Item>
      </Nav>
      <div className="p-4 border-left border-right border-bottom">
        {tab === 'general' && <GeneralView />}
        {tab === 'drivers' && (
          <>
            {!!selectedRaceDriver && (
              <DriverEditView />
            )}
            {!selectedRaceDriver && (
              <DriversListView />
            )}
          </>
        )}
      </div>
    </>
  );
}

export default App;
