<template>
    <div :class="cols">
        <div class="widget" :class="widgetClass">
            <section class="widget__body">
                <div class="widget__status d-flex"
                     v-for="item in content.items">
                    <img class="widget__status-icon" :src="item.icon">
                    <div class="d-flex flex-column text-center justify-content-center">
                        <div class="widget__status-label display-3">{{item.label}}&deg;</div>
                        <div class="widget__status-data h2">{{item.data}}</div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import Widget from './Widget.vue';

  export default {
    extends: Widget,
  };
</script>

<style lang="scss" scoped>
    .widget__status-icon {
        height: auto;
        max-width: 150px;
        display: block;
        margin: 0 auto;
    }
</style>