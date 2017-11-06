import Vue from 'vue';

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
    time: {},
  },
};

export const mapData = (uri, data) => {
  const splitUri = uri.split('/');
  const dataType = splitUri.pop();
  const locationId = splitUri.pop();

  // state.appData[dataType][locationId] = Object.assign({}, data);
  Vue.set(state.appData[dataType], locationId, data);
};