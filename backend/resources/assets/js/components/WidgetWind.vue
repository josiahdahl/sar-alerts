<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header">
                Wind
            </header>
            <section class="widget__body row">
                <div class="col-auto d-flex"
                     v-for="item in items">
                    <div class="d-flex flex-column text-center justify-content-center mx-2">
                        <div class="widget__status-label h3"><strong>{{item.city}}</strong></div>
                        <div class="widget__status-data h2">{{item.windSpeed | toFixed(0)}}
                            <small>km/h</small>
                        </div>
                        <div class="widget__status-data h5">{{item.windDirection}}</div>
                        <div class="text-center">Updated<br> {{ item.created.date | formatLastUpdated }}</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import {format} from 'date-fns';
  import Widget from './Widget.vue';
  import {state} from '../store';
  import * as api from '../api';
  import {toFixed} from '../util/filters';

  export default {
    extends: Widget,
    data() {
      return {
        state,
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
      toFixed,
      formatLastUpdated(val) {
        return format(val, 'MMM DD HH:mm')
      },
    },
    mounted() {
      this.getData();
    },
  };
</script>
