import * as React from 'react';
import {
  useSelector
} from 'react-redux';

import {
  selectPositions,
} from '../store/appSlice';

function Positions() {
  const positions = useSelector(selectPositions);
  const lap = typeof positions[0] !== 'undefined'
    ? positions[0].lap
    : 1;

  return (
    <div className="gui-positions-wrapper">
      <div>Lap {lap}/{appData.race.laps}</div>
      <table>
        <tbody>
          {positions.map((entry) => {
            return (
              <tr key={entry.code}>
                <td className="position">{entry.position}</td>
                <td className="code">{entry.code}</td>
                <td className="lap">{!entry.retired ? entry.lap : 'OUT'}</td>
              </tr>
            )
          })}
        </tbody>
      </table>
    </div>
  );
}

export default Positions;
