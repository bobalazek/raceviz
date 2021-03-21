// Seasons
export const API_GET_SEASONS = '/api/v1/seasons';
export const API_GET_SEASONS_DRIVERS = '/api/v1/seasons/{seasonSlug}/drivers';
export const API_GET_SEASONS_TEAMS = '/api/v1/seasons/{seasonSlug}/teams';

// Races - Drivers
export const API_GET_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers';
export const API_POST_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers';
export const API_POST_RACES_DRIVERS_PREPARE_ALL = '/api/v1/races/{raceSlug}/drivers/prepare-all';
export const API_PUT_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}';
export const API_DELETE_RACES_DRIVERS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}';

// Races - Drivers - Laps
export const API_GET_RACES_DRIVERS_LAPS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps';
export const API_PUT_RACES_DRIVERS_LAPS = '/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps';

// Races - Incidents
export const API_GET_RACES_INCIDENTS = '/api/v1/races/{raceSlug}/incidents';
export const API_POST_RACES_INCIDENTS = '/api/v1/races/{raceSlug}/incidents';
export const API_PUT_RACES_INCIDENTS = '/api/v1/races/{raceSlug}/incidents/{raceIncidentId}';
export const API_DELETE_RACES_INCIDENTS = '/api/v1/races/{raceSlug}/incidents/{raceIncidentId}';

// Races - Incidents - Race Drivers
export const API_GET_RACES_INCIDENTS_RACE_DRIVERS = '/api/v1/races/{raceSlug}/incidents/{raceIncidentId}/race-drivers';
export const API_POST_RACES_INCIDENTS_RACE_DRIVERS = '/api/v1/races/{raceSlug}/incidents{raceIncidentId}/race-drivers';
export const API_PUT_RACES_INCIDENTS_RACE_DRIVERS = '/api/v1/races/{raceSlug}/incidents/{raceIncidentId}/race-drivers/{raceIncidentRaceDriverId}';
export const API_DELETE_RACES_INCIDENTS_RACER_DRIVERS = '/api/v1/races/{raceSlug}/incidents/{raceIncidentId}/race-drivers/{raceIncidentRaceDriverId}';

// Ergast
export const API_GET_ERGAST_RACE_DRIVER_RACE_LAPS = '/api/v1/ergast/{raceSlug}/{raceDriverId}/laps';
export const API_POST_ERGAST_RACE_DRIVER_LAPS_PREPARE_ALL = '/api/v1/ergast/{raceSlug}/race-driver-laps-prepare-all';
