<template>
    <div class="container mx-auto">
        <Tides></Tides>
        <DashboardNotices :forecast="notices.forecast"
                          :warnings="notices.warnings"></DashboardNotices>
    </div>
</template>

<script>
  import axios from 'axios';
  import _get from 'lodash/get';
  import Tides from '../components/widgets/Tides';
  import DashboardNotices from '../components/widgets/DashboardNotices';

  const MINUTE = 1000 * 60;
  const HOUR = MINUTE * 60;
  const DAY = HOUR * 24;

  export default {
    name: 'Dashboard',
    data() {
      return {
        station: null,
        stationData: {
          notices: undefined,
        },
      };
    },
    computed: {
      notices() {
        return _get(this.stationData.notices, 'data', {});
      }
    },
    methods: {
      load(url, key, intervalTime) {
        axios.get(url)
            .then(({ data }) => {
              const oldInterval = _get(this.stationData, `${key}.interval`);
              if (oldInterval) {
                clearInterval(oldInterval);
              }
              const interval = setInterval(() => {
                console.log('loading');
                this.load(url, key, interval);
              }, intervalTime);

              this.$set(this.stationData, key, { data, interval, error: undefined });
            })
            .catch(error => {
              const oldInterval = _get(this.stationData, `${key}.interval`);
              if (oldInterval) {
                clearInterval(oldInterval);
              }
              this.$set(this.stationData, key, { data: undefined, interval: undefined, error })
            });
      }
    },
    mounted() {
      const { data } = window.pageData;
      this.station = data.station;

      const endpoint = dataType => `/api/v1/station/${this.station.id}/${dataType}`;

      this.load(endpoint('notices'), 'notices', HOUR);
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