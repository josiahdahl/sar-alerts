import Vue from 'vue';
import Widget from './components/Widget.vue';
import WidgetWeather from './components/WidgetWeather.vue';
import WidgetTime from './components/WidgetTime.vue';
import WidgetWind from './components/WidgetWind.vue';
import WidgetNotice from './components/WidgetNotice.vue';

new Vue({
  el: '#app',
  data: {
    layout: window.pageData.layout,
  },
  components: {
    Widget,
    WidgetWeather,
    WidgetTime,
    WidgetWind,
    WidgetNotice,
  }
});