<template>
    <Widget :title="title">
        <div v-if="tideData.previous && tideData.next" class="text-white text-lg">
            <p class="text-md mb-1"><b>State:</b> {{currentState}} <b>Height:</b> {{currentHeight}}ft</p>
            <p class="text-md"><b>{{nextType}}:</b> {{nextHeight}}ft @ {{nextTime}}</p>
        </div>
    </Widget>
</template>

<script>
  import parse from 'date-fns/parse';
  import format from 'date-fns/format';
  import differenceInMinutes from 'date-fns/difference_in_minutes';
  import {MINUTE} from "../../util/times";
  import {mToFt} from "../../util/filters";
  import Widget from './common/Widget';

  export default {
    data() {
      return {
        now: undefined,
        nowInterval: undefined,
      }
    },
    props: {
      location: Object,
      tides: {
        type: Array,
        default() {
          return [];
        },
      },
    },
    computed: {
      title() {
        return `Tides: ${this.location.name}`;
      },
      tideData() {
        return this.tides.reduce((data, tide, idx, arr) => {
          const {
            previous,
            next,
          } = data;
          const difference = differenceInMinutes(this.now, parse(tide.time));
          if (difference >= 0) {
            // Tide time is before current time - this will always select the most recent high/low tide and the one
            // immediately following, thereby giving us the previous and next
            return {
              previous: tide,
              previousIndex: idx,
              next: arr.length > idx + 2 ? arr[idx + 1] : undefined,
              nextIndex: arr.length > idx + 2 ? idx + 1 : undefined,
            }
          }
          return data;
        }, {previous: undefined, next: undefined, previousIndex: -1, nextIndex: -1});
      },
      currentState() {
        const tideData = this.tideData;
        if (Math.abs(differenceInMinutes(this.now, parse(tideData.previous.time)) < 60
          || Math.abs(differenceInMinutes(this.now, parse(tideData.next.time))) < 60)) {
          return 'Slack';
        }
        return tideData.next.high_low === 'high' ? 'Flooding' : 'Ebbing';
      },
      currentHeight() {
        const timeToNext = Math.abs(differenceInMinutes(this.now, parse(this.tideData.next.time)));
        const totalTime = Math.abs(differenceInMinutes(
          parse(this.tideData.previous.time),
          parse(this.tideData.next.time))
        );
        const totalHeightDiff = Math.abs(this.tideData.previous.height - this.tideData.next.height);
        const currentHeightDiff = timeToNext / totalTime * totalHeightDiff;

        return this.tideData.next.high_low === 'high'
          ? mToFt(this.tideData.next.height - currentHeightDiff).toFixed(1)
          : mToFt(this.tideData.previous.height - currentHeightDiff).toFixed(1);
      },
      nextType() {
        return this.tideData.next.high_low === 'high'
          ? 'High Tide'
          : 'Low Tide';
      },
      nextHeight() {
        return mToFt(this.tideData.next.height).toFixed(1);
      },
      nextTime() {
        return format(parse(this.tideData.next.time), 'HH:MM');
      },
    },
    mounted() {
      this.now = parse(Date.now());
      this.nowInterval = setInterval(() => {
        this.now = parse(Date.now());
      }, MINUTE * 10);
    },
    beforeDestroy() {
      clearInterval(this.nowInterval);
    },
    components: {
      Widget,
    }
  };
</script>

<style>

</style>