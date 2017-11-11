<template>
    <div>
        <p class="mb-0">Last Updated</p>
        <p v-for="location in ageOfData" class="mb-0">
            <b>{{location.city}}</b> - {{location.updated}}
        </p>
    </div>
</template>

<script>
  import {format} from 'date-fns';
  import {state} from '../store';

  export default {
    data() {
      return {
        state,
      };
    },
    props: [],
    computed: {
      ageOfData() {
        return Object.keys(this.state.appData.weather).reduce((carry, key) => {
          const item = this.state.appData.weather[key];
          const updated = format(item.created.date, 'MMM DD HH:mm');
          const city = item.city;
          carry.push({ updated, city });
          return carry;
        }, []);
      },
    }
  };
</script>

<style>

</style>