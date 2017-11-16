import Axios from 'axios';
import * as store from '../store';

const scheduling = {};

const heartbeats = {
  intervals: {},
  lastUpdated: {},
};

export const SECOND = 1000;
export const MINUTE = SECOND * 60;

export const get = uri => Axios.get(uri)
    .then((res) => {
      if (res.status === 200) {
        store.mapData(uri, res.data.data);
        return Promise.resolve();
      }
      return Promise.reject();
    });

export const schedule = (uri, interval = (MINUTE * 15)) => {
  if (scheduling[uri]) {
    console.log(`Cleared: ${uri}`);
    clearInterval(scheduling[uri]);
    delete(scheduling[uri]);
  }
  scheduling[uri] = setInterval(() => {
    get(uri);
  }, interval);
};

export const startHeartbeat = (uri) => {
  console.log(`Starting heartbeat for ${uri}`);
  if (heartbeats.intervals[uri]) {
    clearInterval(heartbeats.intervals[uri]);
    delete(heartbeats.intervals[uri]);
  }
  heartbeats.lastUpdated[uri] = Date.now();
  heartbeats.intervals[uri] = setInterval(() => {
    console.log(`Checking heartbeat for: ${uri}`);
    const timeDiff = Date.now() - heartbeats.lastUpdated[uri];
    if (timeDiff > (SECOND * 15)) {
      console.log(`Updating: ${uri}`);
      get(uri).then(() => schedule(uri))
          .then(() => heartbeats.lastUpdated[uri] = Date.now());
    } else {
      heartbeats.lastUpdated[uri] = Date.now();
    }
  }, (SECOND * 10));

};
