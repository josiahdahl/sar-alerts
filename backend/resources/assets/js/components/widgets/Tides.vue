<template>
    <div>
        <WidgetHeader>Tides</WidgetHeader>
        <p class="text-md"><b>{{currentState}}:</b> {{currentHeight}}ft</p>
        <p class="text-md"><b>{{nextType}}:</b> {{nextHeight}}ft @ {{nextTime}}</p>
    </div>
</template>

<script>
  import WidgetHeader from './common/WidgetHeader';

  export default {
    props: {
      currentState: String,
      currentHeight: Number,
      nextType: String,
      nextHeight: Number,
      nextTime: String,
    },
    components: {
      WidgetHeader,
    }
  };
</script>

<style>

</style>