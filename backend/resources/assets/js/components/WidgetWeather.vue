<template>
    <div :class="cols" class="d-flex flex-column justify-content-center">
        <div class="widget w-weather">
            <section class="widget__body">
                <div class="widget__status d-flex flex-column"
                     v-for="item in items">
                    <div class="d-flex flex-wrap">
                        <img class="widget__status-icon" :src="item.icon">
                        <div class="d-flex flex-column text-center justify-content-center">
                            <div class="widget__status-label display-3">{{item.temperature | toFixed(0) }}&deg;</div>
                            <p class="widget__status-data h2 text-capitalize">{{item.main}}</p>
                        </div>
                        <p class="w-weather__description text-capitalize text-right mb-0 col px-0">{{item.description}}</p>
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
  import {toFixed} from '../util/filters';
  import Widget from './Widget.vue';


  export default {
    extends: Widget,
    data() {
      return {
        state,
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
        this.dataSources.forEach(
            (source) => {
              api.get(source.endpoint)
                  .then(() => {
                    api.schedule(source.endpoint);
                    api.startHeartbeat(source.endpoint);
                  })
            });
      }
    },
    filters: {
      toFixed,
      formatLastUpdated(val) {
        return format(val, 'MMM DD HH:mm')
      },
    },
    mounted() {
      this.getData();
    }
  };
</script>

<style lang="scss" scoped>
    /*@import '../../sass/variables';*/

    .widget__status {
        position: relative;
    }

    .widget__status-icon {
        height: 150px;
        display: block;
        margin: 0 auto;
    }

    .w-weather__description {
        position: absolute;
        bottom: -1rem;
        left: 0;
        right: 0;
    }
</style>