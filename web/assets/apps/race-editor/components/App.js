import React, {
  useState,
} from 'react';
import {
  Nav,
}  from 'react-bootstrap';

import GeneralPage from './Pages/General/Page';
import DriversPage from './Pages/Drivers/Page';

function App() {
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
        {tab === 'general' && <GeneralPage />}
        {tab === 'drivers' && <DriversPage />}
      </div>
    </>
  );
}

export default App;
