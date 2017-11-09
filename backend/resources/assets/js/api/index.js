import Axios from 'axios';
import * as store from '../store';


export const get = uri => Axios.get(uri)
    .then((res) => {
      if (res.status === 200) {
        store.mapData(uri, res.data.data);
        return Promise.resolve();
      }
      return Promise.reject();
    });

