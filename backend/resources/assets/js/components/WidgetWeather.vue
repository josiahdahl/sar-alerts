<template>
    <div :class="cols">
        <div class="widget">
            <section class="widget__body">
                <div class="widget__status d-flex"
                     v-for="item in items">
                    <img class="widget__status-icon" :src="defaultIcon">
                    <div class="d-flex flex-column text-center justify-content-center">
                        <div class="widget__status-label display-3">{{item.temperature | toFixed }}&deg;</div>
                        <div class="widget__status-data h2">{{item.shortDescription}}</div>
                    </div>
                </div>
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
        defaultIcon: '/images/icons/heavy_rain_showers.svg',
        endpoints: null,
      };
    },
    computed: {
      items() {
        return this.dataSources.reduce((items, source) => {
          const item = this.state.appData[source.dataType][source.locationId];
          if (item) {
            items.push(item);
          }
          return items;
        }, []);
      },
    },
    methods: {
      getData() {
        this.dataSources.forEach(source => api.get(source.endpoint));
      }
    },
    filters: {
      toFixed(val) {
        return val.toFixed(1);
      }
    },
    mounted() {
      this.getData();
    }
  };
</script>

<style lang="scss" scoped>
    .widget__status-icon {
        height: auto;
        max-width: 150px;
        display: block;
        margin: 0 auto;
    }
</style>