<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header display-4">
                Tides
            </header>
            <section class="widget__body">
                <div class="row flex-grow">
                    <div class="col col-sm-4">
                        <div class="h1">Flooding</div>
                        <div class="display-3">3.2</div>
                    </div>
                    <div class="col col-sm-8 text-right w-tide__next" v-if="nextTide">
                        <div class="h1">{{nextTide.high_low}} Tide</div>
                        <div class="display-3">{{nextTide.time | removeSeconds }} {{nextTide.height}}
                            <small>m</small>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import {isAfter, format} from 'date-fns';
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
    filters: {
      removeSeconds(time) {
        return time.split(':').slice(0, 2).join(':');
      },
    },
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