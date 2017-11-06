<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header display-4">
                Alerts
            </header>
            <section class="widget__body">
                <p>Nothing yet</p>
                <!--<div class="widget__status d-flex"-->
                <!--v-for="item in content.items">-->
                <!--<div class="d-flex flex-column">-->
                <!--<div class="widget__status-data h3 text-left">{{item.content}}<br>-->
                <!--<small>{{ item.created | dateTime }}</small>-->
                <!--</div>-->

                <!--</div>-->
                <!--</div>-->
            </section>
        </div>
    </div>
</template>

<script>
  import {format} from 'date-fns';
  import Widget from './Widget.vue';

  export default {
    extends: Widget,
    filters: {
      dateTime(dateTime) {
        return format(dateTime, 'MMM D, YYYY HH:mm');
      }
    }
  };
</script>
