import Vue from 'vue';
import {format} from 'date-fns';

export const state = {
  appData: {
    weather: {
      /*  // Inside are location IDs with the information
        1: {},
        2: {},
       */
    },
    tides: {},
    notices: {},
    time: {
      currentTime: new Date(),
    },
  },
  lastUpdated: {}
};

export const mapData = (uri, data) => {
  const splitUri = uri.split('/');
  const dataType = splitUri.pop();
  const locationId = splitUri.pop();

  Vue.set(state.appData[dataType], locationId, data);
};