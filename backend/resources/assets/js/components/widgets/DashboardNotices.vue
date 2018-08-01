<template>
    <Widget title="Alerts">
        <DashboardNotice v-if="warnings"
                         v-for="(warning, i) of warnings"
                         :key="i"
                         :title="warning.title"
                         :updated="warning.updated"
                         variant="warning"></DashboardNotice>
        <DashboardNotice v-if="forecast"
                         :title="forecast.title"
                         :summary="forecast.summary"
                         :updated="forecast.updated"
                         variant="info"></DashboardNotice>
    </Widget>

</template>

<script>
  import DashboardNotice from './DashboardNotice'
  import Widget from './common/Widget';

  export default {
    name: 'DashboardNotices',
    props: {
      warnings: Array,
      forecast: Object,
    },
    components: {
      DashboardNotice,
      Widget,
    },
  }
</script>

<style scoped>

</style>