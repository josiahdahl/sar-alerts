import {ApiDataProvider} from './api-data.provider';


export class DashboardProvider {
  _provider;
  _station;
  _isHydrated = false;
  _urls;
  _data = {
    weather: null,
    time: null,
    tides: null,
    notices: null,
  };

  constructor({ weather, time, tides, notices, station }) {
    this._provider = new ApiDataProvider();
    this._station = station;
    this._saveUrls(weather, time, tides, notices);
    this._hydrate();
  }

  get station() {
    return this._station;
  }

  get isHydrated() {
    return this._isHydrated;
  }

  weather() {
    return this._urls.weather.map(url => this._provider.get(url, HOUR));
  }

  localTime() {
    return this._urls.time.map(url => this._provider.get(url, MINUTE));
  }

  tides() {
    return this._urls.tides.map(url => this._provider.get(url, 12 * HOUR));
  }

  notices() {
    return this._urls.notices.map(url => this._provider.get(url, HOUR));
  }

  _saveUrls(weather, time, tides, notices) {
    this._urls.weather = weather.map(w => w.endpoint);
    this._urls.time = time.map(t => t.endpoint);
    this._urls.tides = tides.map(t => t.endpoint);
    this._urls.notices = notices.map(t => t.endpoint);
  }

  _hydrate() {
    Promise.all([
      this._provider.hydrate(this._urls.weather),
      this._provider.hydrate(this._urls.time),
      this._provider.hydrate(this._urls.tides),
      this._provider.hydrate(this._urls.notices),
    ]).then(() => {
      this._isHydrated = true;
    });
  }

  _subscribe(interval, urls) {

  }
}