<template>
    <div :class="cols">
        <div class="widget flex-column justify-content-center w-time">
            <section class="widget__body">
                <div class="w-time__time">{{currentTime}}</div>
                <div class="w-time__date">{{currentDate}}</div>
            </section>
        </div>
    </div>
</template>

<script>
  import Widget from './Widget.vue';

  const leftPad = (string, length, char = ' ') => {
    const longString = char.repeat(length) + string;
    return longString.substring((longString.length - length));
  };

  export default {
    extends: Widget,
    data() {
      return {
        dateObj: null,
        interval: null,
      };
    },
    computed: {
      currentDate() {
        const [day, month, date, year] = this.dateObj.toDateString().split(' ');
        return `${month} ${date}, ${year}`;
      },
      currentTime() {
        return `${leftPad(this.dateObj.getHours(), 2, '0')}:${leftPad(this.dateObj.getMinutes(), 2, '0')}:${leftPad(this.dateObj.getSeconds(), 2, '0')}`;
      },
    },
    methods: {
      setNewDateObj() {
        this.dateObj = new Date();
      }
    },
    created() {
      this.setNewDateObj();
    },
    mounted() {
      this.interval = setInterval(() => {
        this.setNewDateObj();
      }, 1000);
    },
    beforeDestroy() {
      this.clearInterval(this.interval);
    },
  };
</script>

<style lang="scss" scoped>
    @import '../../sass/variables';

    .widget {
        height: 100%;
    }

    .widget__body {
        text-align: right;
    }

    .w-time {
        display: none;
        @include media-breakpoint-up(sm) {
            display: flex;
            font-size: 1.2rem;
        }

        &__time {
            font-size: 3em;
        }
        &__date {
            font-size: 2em;
        }
    }
</style>