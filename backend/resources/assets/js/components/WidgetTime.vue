<template>
    <div :class="cols">
        <div class="widget w-time justify-content-end">
            <section class="widget__body flex-column justify-content-center">
                <div class="w-time__time">{{currentTime}}</div>
                <div class="w-time__date">{{currentDate}}</div>
            </section>
        </div>
    </div>
</template>

<script>
  import {format, addSeconds} from 'date-fns';
  import {state} from '../store';
  import * as api from '../api';
  import Widget from './Widget.vue';

  export default {
    extends: Widget,
    data() {
      return {
        state,
        interval: null,
        endpoint: null,
        dateTime: null,
      };
    },
    computed: {
      locationId() {
        return this.dataSources[0].locationId || false;
      },
      currentDate() {
        return format(this.dateTime, 'MMM DD, YYYY');
      },
      currentTime() {
        return format(this.dateTime, 'HH:mm:ss');
      },
    },
    methods: {
      updateSeconds() {
        this.dateTime = addSeconds(this.dateTime, 1);
      },
    },
    mounted() {
      this.dateTime = new Date();
      this.endpoint = this.dataSources[0].endpoint;
      api.get(this.endpoint)
          .then(() => {
            this.dateTime = this.state.appData.time[this.locationId].time;
          });

      this.interval = setInterval(this.updateSeconds, 1000);
    },
    beforeDestroy() {
      this.clearInterval(this.interval);
    },
  };
</script>

<style lang="scss" scoped>
    @import '../../sass/variables';

    .widget {
        height: 100%;
    }

    .widget__body {
        text-align: right;
    }

    .w-time {
        display: none;
        @include media-breakpoint-up(sm) {
            display: flex;
            font-size: 1.2rem;
        }

        &__time {
            font-size: 3em;
        }
        &__date {
            font-size: 2em;
        }
    }
</style>