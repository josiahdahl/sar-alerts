import Vue from 'vue';
import Widget from './components/Widget.vue';
import WidgetWeather from './components/WidgetWeather.vue';
import WidgetTime from './components/WidgetTime.vue';
import WidgetWind from './components/WidgetWind.vue';
import WidgetNotice from './components/WidgetNotice.vue';

const pageData = {
  layout: [
    [{
      widget: 'WidgetWeather',
      sizes: {
        sm: 4,
      },
      widgetClass: '',
      content: {
        items: [{
          icon: '/images/icons/heavy_rain_showers.svg',
          label: '10',
          data: 'Fog',
        }],
      }
    }, {
      widget: 'WidgetTime',
      sizes: {
        sm: 8,
      },
      widgetClass: 'w-time',
      content: {
        items: []
      }
    }], [{
      widget: 'Widget',
      sizes: {
        sm: 12,
      },
      widgetClass: 'w-tides',
      content: {
        title: 'Tides',
        items: [{
          label: 'Flooding',
          data: '3.2m',
        }, {
          label: 'High Tide',
          data: '14:53 3.2m',
        }],
      },
    }],
    [{
      widget: 'WidgetWind',
      sizes: {
        sm: 12,
      },
      widgetClass: 'w-wind',
      content: {
        title: 'Wind',
        items: [{
          direction: 'NW',
          speed: '16',
          location: 'Sooke',
        }, {
          direction: 'NW',
          speed: '12',
          location: 'Port Renfrew',
        }]
      },
    }],
    [{
      widget: 'WidgetNotice',
      sizes: {
        sm: 12,
      },
      widgetClass: '',
      content: {
        title: 'Warning',
        items: [{
          content: 'Small craft advisory for Juan de Fuca Strait',
          created: 'Wed Nov 01 2017 14:36:36 GMT-0700 (PDT)',
        }],
      },
    }],
  ],
};

new Vue({
  el: '#app',
  data: {
    layout: pageData.layout,
  },
  components: {
    Widget,
    WidgetWeather,
    WidgetTime,
    WidgetWind,
    WidgetNotice,
  }
});