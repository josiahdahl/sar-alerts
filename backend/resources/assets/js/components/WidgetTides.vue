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
  // TODO: Update this widget to be tide specific
  export default {
    data() {
      return {};
    },
    props: ['sizes', 'widgetClass', 'content'],
    computed: {
      cols() {
        return Object.keys(this.sizes).reduce((cols, size) => {
          const sizeModifier = this.sizes[size];
          if (size === '') {
            cols.push(`col-${sizeModifier}`);
          } else {
            cols.push(`col-${size}-${sizeModifier}`);
          }
          return cols;
        }, []);
      },
    },
  };
</script>

<style lang="scss" scoped>
    @import '../../sass/variables';

    .widget {
        &__header {
            border-bottom: 1px solid grayscale(200);
            text-transform: uppercase;
        }
        &__body {
            padding: map_get($spacers, 4);

            display: flex;
        }
        &__status {
            flex: 1;
            &:last-child {
                text-align: right;
            }
        }
        &__status-label {
        }
        &__status-data {
        }
    }
</style>