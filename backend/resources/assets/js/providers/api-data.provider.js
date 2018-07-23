import axios from 'axios';
import _has from 'lodash/has';
import _get from 'lodash/get';

export class ApiDataProvider {
  _cache;

  hydrate(urls) {
    return Promise.all(urls.map(url => this.get(url, 0)))
  }

  get(url, timeout) {
    if (this._cacheHasData(url) && !this._shouldRefreshCache(url, timeout)) {
      const cachedData = _get(this._cache[url]);
      return cachedData ? Promise.resolve(cachedData) : this._load(url);
    }
    return this._load(url);
  }

  _load(url) {
    return axios.get(url)
        .then(({ data }) => {
          this._storeCache(url, data);
          return Promise.resolve(data);
        });
  }

  _shouldRefreshCache(url, timeout) {
    const {
      data,
      updated
    } = _get(this._cache[url]);

    return !data || (updated + timeout) < Date.now();
  }

  _cacheHasData(url) {
    return _has(this._cache, url);
  }

  _storeCache(url, data) {
    this._cache = {
      ...this._cache,
      [url]: {
        data,
        updated: Date.now(),
      },
    };
  }

}