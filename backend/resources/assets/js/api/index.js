import Axios from 'axios';
import * as store from '../store';

const apiBase = 'http://sar-dashboard.local';

export const get = uri => Axios.get(`${apiBase}${uri}`)
    .then((res) => {
      if (res.status === 200) {
        store.mapData(uri, res.data.data);
      }
    });

