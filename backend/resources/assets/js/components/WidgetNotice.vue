<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header">
                Alerts
            </header>
            <section class="widget__body">
                <div>
                    <div class="w-notice__warnings" v-for="item in items">
                        <div class="w-notice__warning" v-for="warning in item.warnings">
                            <h4 class="text-danger">{{warning.title}}</h4>
                            <p>Updated: {{warning.updated | formatLastUpdated}}</p>
                        </div>
                    </div>
                    <div v-for="item in items">
                        <h4>{{item.forecast.title}}</h4>
                        <p class="lead">{{item.forecast.summary}}</p>
                        <p>Updated: {{item.forecast.updated | formatLastUpdated}}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import {format} from 'date-fns';
  import {state} from '../store';
  import * as api from '../api';
  import Widget from './Widget.vue';

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
        this.dataSources.forEach(source => api.get(source.endpoint)
            .then(() => api.schedule(source.endpoint)));
      }
    },
    mounted() {
      this.getData();
    },
    filters: {
      formatLastUpdated(val) {
        return format(val, 'MMM DD HH:mm')
      },
    },
  };
</script>
