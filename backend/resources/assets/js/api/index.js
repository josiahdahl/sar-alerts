import Axios from 'axios';
import * as store from '../store';

const scheduling = {};

export const MINUTE = 1000 * 60;

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
    clearInterval(scheduling[uri]);
    delete(scheduling[uri]);
  }
  scheduling[uri] = setInterval(() => {
    get(uri);
  }, interval);
};
