<template>
    <div class="text-white px-2 my-3" :class="variantClass">
        <p class="notice__title mb-2">{{title}}</p>
        <p v-if="summary">{{summary}}</p>
        <p class="text-sm mt-2">{{updatedFormatted}}</p>
    </div>
</template>

<script>
  import {parse, format} from 'date-fns';
  import WidgetHeader from './common/WidgetHeader';

  export default {
    name: 'DashboardNotice',
    props: {
      title: String,
      summary: String,
      updated: String,
      variant: {
        validator: (value) => {
          return ['info', 'warning'].indexOf(value) !== -1;
        }
      }
    },
    computed: {
      variantClass() {
        return `notice notice--${this.variant}`
      },
      updatedFormatted() {
        return format(parse(this.updated), 'MMM D HH:mm');
      }
    },
    components: {
      WidgetHeader,
    }
  }
</script>

<style scoped>
    .notice {
        border-left-width: 3px;
        border-left-style: solid;
        border-left-color: #8e8e8e;
    }

    .notice--info {
        border-left-color: #03CEA4;
    }

    .notice--warning {
        border-left-color: #BA3B46;
    }

    .notice__title {
        font-weight: bold;
        letter-spacing: 0.5px;
    }
</style>