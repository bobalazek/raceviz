import * as React from 'react';
import {
  useSelector
} from 'react-redux';

import {
  selectPositions,
} from '../store/appSlice';

function Positions() {
  const positions = useSelector(selectPositions);

  return (
    <div className="gui-positions-wrapper">
      <div>LAPS TODO</div>
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
