<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header">
                Tides
            </header>
            <section class="widget__body">
                <div class="row flex-grow">
                    <div class="col col-sm-4 col-md-6">
                        <div class="h2">Current Status</div>
                        <div class="h3">Flooding 3.2ft</div>
                    </div>
                    <div class="col col-sm-8 col-md-6 text-right w-tide__next" v-if="nextTide">
                        <div class="h2">{{nextTide.high_low}} Tide {{nextTide.time | removeSeconds }}</div>
                        <div class="h1">Height: {{nextTide.height | mToFt | toFixed(1) }}
                            <small>ft</small>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import {isAfter, format} from 'date-fns';
  import {filters} from '../util';
  import Widget from './Widget.vue';
  import {state} from '../store';
  import * as api from '../api';

  export default {
    extends: Widget,
    data() {
      return {
        state,
      };
    },
    computed: {
      currentTime() {
        if (typeof this.state.appData.time[this.locationId] !== 'undefined') {
          return this.state.appData.time[this.locationId].time;
        }
//        return new Date().toISOString();
      },
      tides() {
        return this.state.appData.tides[this.locationId] || [];
      },
      nextTide() {
        return this.tides.find(tide => {
          return isAfter(new Date(`${tide.date} ${tide.time} ${tide.timezone}`), new Date(this.currentTime));
        });
      },
      locationId() {
        return this.dataSources[0].locationId;
      },
    },
    filters,
    methods: {
      getData() {
        this.dataSources.forEach(source => api.get(source.endpoint));
      },
    },
    mounted() {
      this.getData();
    },
  };
</script>

<style lang="scss" scoped>
    .widget__body {
        display: block;
    }

    .w-tide__next {
        text-transform: capitalize;
    }
</style>