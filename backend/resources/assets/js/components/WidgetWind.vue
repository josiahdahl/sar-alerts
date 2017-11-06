<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header display-4">
                Wind
            </header>
            <section class="widget__body row">
                <div class="col-auto d-flex"
                     v-for="item in items">
                    <div class="d-flex flex-column text-center justify-content-center mx-2">
                        <div class="widget__status-label h2">{{item.city}}</div>
                        <div class="widget__status-data h1">{{item.windSpeed}}
                            <small>km/h</small>
                        </div>
                        <div class="widget__status-data h4">{{item.windDirection}}</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
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
    mounted() {
      this.getData();
    },
  };
</script>
