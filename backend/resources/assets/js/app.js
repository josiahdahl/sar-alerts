import Vue from 'vue';
import Widget from './components/Widget.vue';
import WidgetWeather from './components/WidgetWeather.vue';
import WidgetTime from './components/WidgetTime.vue';
import WidgetWind from './components/WidgetWind.vue';
import WidgetNotice from './components/WidgetNotice.vue';
import WidgetTides from './components/WidgetTides.vue';
import DataAge from './components/DataAge.vue';
import Dashboard from './containers/Dashboard';

new Vue({
  el: '#app',

  render: h => h(Dashboard),
  // components: {
  //   Widget,
  //   WidgetWeather,
  //   WidgetTime,
  //   WidgetWind,
  //   WidgetNotice,
  //   WidgetTides,
  //   DataAge,
  // },
});