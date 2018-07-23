<template>
    <div>
        <WidgetHeader>Alerts</WidgetHeader>
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
    </div>
</template>

<script>
  import DashboardNotice from './DashboardNotice'
  import WidgetHeader from './common/WidgetHeader';

  export default {
    name: 'DashboardNotices',
    props: {
      warnings: Array,
      forecast: Object,
    },
    components: {
      DashboardNotice,
      WidgetHeader,
    },
  }
</script>

<style scoped>

</style>