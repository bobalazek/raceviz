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
  setData,
  selectData,
} from '../../../../store/selectedRaceDriverSlice';
import FormEdit from './FormEdit';
import FormLaps from './FormLaps';

function Section() {
  const store = useStore();
  const selectedRaceDriver = useSelector(selectData);

  const [tab, setTab] = useState('general');

  const onBackToListClick = () => {
    store.dispatch(setData(null));
  };

  return (
    <>
      <h2>
        <span>{selectedRaceDriver?.season_driver?.driver.name} </span>
        <small>({selectedRaceDriver?.season_driver?.team.name}) </small>
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
        {tab === 'general' && <FormEdit selectedRaceDriver={selectedRaceDriver} />}
        {tab === 'laps' && <FormLaps selectedRaceDriver={selectedRaceDriver} />}
      </div>
    </>
  );
}

export default Section;
