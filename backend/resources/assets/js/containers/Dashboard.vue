<template>
    <div class="container mx-auto">
        <Tides v-for="(location, idx) in tides"
               :key="idx"
               :tides="location.tides"
               :location="location.location"></Tides>
        <DashboardNotices :forecast="notices.forecast"
                          :warnings="notices.warnings"></DashboardNotices>
    </div>
</template>

<script>
  import axios from 'axios';
  import _get from 'lodash/get';
  import Tides from '../components/widgets/Tides';
  import DashboardNotices from '../components/widgets/DashboardNotices';
  import {HOUR} from "../util/times";

  export default {
    name: 'Dashboard',
    data() {
      return {
        station: null,
        stationData: {
          notices: undefined,
          tides: undefined,
        },
      };
    },
    computed: {
      notices() {
        return _get(this.stationData.notices, 'data', {});
      },
      tides() {
        return _get(this.stationData.tides, 'data', []);
      }
    },
    methods: {
      load(url, key, intervalTime) {
        axios.get(url)
          .then(({data}) => {
            const oldInterval = _get(this.stationData, `${key}.interval`);
            if (oldInterval) {
              clearInterval(oldInterval);
            }
            const interval = setInterval(() => {
              console.log('loading');
              this.load(url, key, interval);
            }, intervalTime);

            this.$set(this.stationData, key, {data, interval, error: undefined});
          })
          .catch(error => {
            const oldInterval = _get(this.stationData, `${key}.interval`);
            if (oldInterval) {
              clearInterval(oldInterval);
            }
            this.$set(this.stationData, key, {data: undefined, interval: undefined, error})
          });
      }
    },
    mounted() {
      const {data} = window.pageData;
      this.station = data.station;

      const endpoint = dataType => `/api/v1/stations/${this.station.id}/${dataType}`;

      this.load(endpoint('notices'), 'notices', HOUR);
      this.load(endpoint('tides'), 'tides', HOUR);
    },
    components: {
      Tides,
      DashboardNotices,
    },
  }
</script>

<style>
    body {
        background-color: #4A4A4A;
        padding: 1rem;
    }
</style>