<template>
    <div class="mb-4">
        <WidgetHeader>{{title}}</WidgetHeader>
        <slot></slot>
    </div>
</template>

<script>
  import WidgetHeader from './WidgetHeader';

  export default {
    name: "Widget",
    props: {
      title: String,
    },
    components: {
      WidgetHeader,
    }
  }
</script>

<style scoped>

</style>