<template>
    <div :class="cols">
        <div class="widget">
            <header class="widget__header">
                Tides
            </header>
            <section class="widget__body">
                <div class="row flex-grow justify-content-between">
                    <div class="col col-auto">
                        <div class="h1"><span class="text-capitalize">{{tideState}}</span>
                            - {{tideEstimatedHeight | mToFt | toFixed(1)}}
                            <small>ft</small>
                        </div>
                    </div>
                    <div class="col col-auto text-right w-tide__next" v-if="nextTide">
                        <div class="h1">
                            <span class="text-capitalize">{{nextTide.high_low}}</span>
                            Tide - {{nextTide.time | removeSeconds}} - {{nextTide.height | mToFt | toFixed(1) }}<small>ft</small>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="v-chart">
                            <canvas class="v-chart__chart"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
  import Chart from 'chart.js';
  import {isAfter, format, differenceInMinutes, parse} from 'date-fns';
  import {filters} from '../util';
  import Widget from './Widget.vue';
  import {state} from '../store';
  import * as api from '../api';

  const tideStates = {
    SLACK: 'slack',
    FLOODING: 'flooding',
    EBBING: 'ebbing',
    UNKNOWN: 'no data',
  };

  const originalLineDraw = Chart.controllers.line.prototype.draw;
  Chart.helpers.extend(Chart.controllers.line.prototype, {
    draw: function () {
      originalLineDraw.apply(this, arguments);

      const chart = this.chart;
      const ctx = chart.chart.ctx;

      const index = chart.config.data.lineAtIndex;
      if (index) {
        const xaxis = chart.scales['x-axis-0'];
        const yaxis = chart.scales['y-axis-0'];

        ctx.save();
        ctx.beginPath();
        ctx.moveTo(xaxis.getPixelForValue(undefined, index), yaxis.top);
        ctx.strokeStyle = '#fff';
        ctx.lineTo(xaxis.getPixelForValue(undefined, index), yaxis.bottom);
        ctx.stroke();
        ctx.restore();
      }
    }
  });

  export default {
    extends: Widget,
    data() {
      return {
        state,
        chart: null,
      };
    },
    computed: {
      currentTime() {
        if (typeof this.state.appData.time[this.locationId] !== 'undefined') {
          return this.state.appData.time[this.locationId].time;
        }
        return new Date();
      },
      tides() {
        return this.state.appData.tides[this.locationId] || [];
      },
      prevTide() {
        const currentTideIndex = this.tides.findIndex(tide => tide === this.nextTide);
        // Since we always get 36 hours of tides, this should never be < 0
        return this.tides[currentTideIndex - 1];
      },
      nextTide() {
        return this.tides.find(tide => {
          return isAfter(parse(`${tide.date} ${tide.time} ${tide.timezone}`), parse(this.currentTime));
        });
      },
      tideState() {
        if (typeof this.prevTide === 'undefined' || typeof this.nextTide === 'undefined') {
          return tideStates.UNKNOWN;
        }
        const diffFromPrev = differenceInMinutes(new Date(`${this.prevTide.date} ${this.prevTide.time} ${this.prevTide.timezone}`), this.currentTime);
        const diffFromNext = differenceInMinutes(this.currentTime, new Date(`${this.nextTide.date} ${this.nextTide.time} ${this.nextTide.timezone}`));
        if ( Math.abs(diffFromPrev) < 60 || Math.abs(diffFromNext) < 60) {
          return tideStates.SLACK;
        }

        const heightDiff = this.prevTide.height - this.nextTide.height;

        if (heightDiff > 0) {
          return tideStates.EBBING;
        }
        return tideStates.FLOODING;
      },
      tideEstimatedHeight() {
        if (typeof this.prevTide === 'undefined' || typeof this.nextTide === 'undefined') {
          return 0;
        }
        const nextTideDateObj = parse(`${this.nextTide.date} ${this.nextTide.time} ${this.nextTide.timezone}`);
        const prevTideDateObj = parse(`${this.prevTide.date} ${this.prevTide.time} ${this.prevTide.timezone}`);
        const timeBeforeNextMinMax = Math.abs(differenceInMinutes(this.currentTime, nextTideDateObj));
        const timeBetweenTides = Math.abs(differenceInMinutes(prevTideDateObj, nextTideDateObj));

        const heightPercentageLeft = timeBeforeNextMinMax / timeBetweenTides;
        let heightDiff = 0;
        if (typeof this.prevTide !== 'undefined' && typeof this.nextTide !== 'undefined') {
          heightDiff = this.prevTide.height - this.nextTide.height;
        }

        const percentRemaining = timeBeforeNextMinMax / timeBetweenTides;
        const heightMoved = heightDiff * percentRemaining;
        return this.nextTide.height + heightMoved;
      },
      locationId() {
        return this.dataSources[0].locationId;
      },
      chartTideData() {
        return this.tides.reduce((carry, tide) => {
          carry.push(filters.mToFt(tide.height));
          return carry;
        }, []);
      },
      chartTideLabels() {
        return this.tides.reduce((carry, tide) => {
          carry.push(tide.time.split(':').slice(0, 2).join(':'));
          return carry;
        }, []);
      },
    },
    filters,
    methods: {
      getData() {
        return this.dataSources.map((source) => api.get(source.endpoint)
            .then(() => {
                api.schedule(source.endpoint, api.MINUTE * 60 * 12);
                api.startHeartbeat(source.endpoint, api.MINUTE);
            }))
      },
    },
    mounted() {
      // Get all of the data and then build the graph
      Promise.all(this.getData())
          .then(() => {
            const ctx = this.$el.querySelector('.v-chart__chart').getContext('2d');
            this.chart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: this.chartTideLabels,
                datasets: [{
                  backgroundColor: 'transparent',
                  borderColor: '#2e97bf',
                  data: this.chartTideData
                }],
                lineAtIndex: this.tides.findIndex(tide => tide === this.nextTide),
              },
              options: {
                defaultFontSize: 16,
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                  display: false,
                },
                scales: {
                  xAxes: [{
                    ticks: {
                      fontSize: 16,
                    }
                  }],
                  yAxes: [{
                    ticks: {
                      fontSize: 16,
                    },
                  }],
                },
              },
            });
          });
    },
  };
</script>

<style lang="scss" scoped>
    .widget__body {
        display: block;
    }
</style>