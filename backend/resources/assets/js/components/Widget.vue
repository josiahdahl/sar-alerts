<template>
    <div :class="cols">
        <div class="widget" :class="widgetClass">
            <header class="widget__header display-4" v-if="content.title">
                {{ content.title }}
            </header>
            <section class="widget__body">
                <div class="widget__status" v-for="item in content.items">
                    <div class="widget__status-label h1">{{item.label}}</div>
                    <div class="widget__status-data h2">{{item.data}}</div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  export default {
    data() {
      return {};
    },
    props: ['sizes', 'widgetClass', 'dataSources'],
    computed: {
      // TODO: Turn this into a mixin
      cols() {
        return Object.keys(this.sizes).reduce((cols, size) => {
          const sizeModifier = this.sizes[size];

          if (sizeModifier === null) return cols;

          cols.push(`col-${size}-${sizeModifier}`);
          return cols;
        }, ['col']);
      },
    },
  };
</script>

<style lang="scss" scoped>

</style>