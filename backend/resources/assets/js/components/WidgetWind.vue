<template>
    <div :class="cols">
        <div class="widget" :class="widgetClass">
            <header class="widget__header display-4" v-if="content.title">
                {{ content.title }}
            </header>
            <section class="widget__body row">
                <div class="col-auto d-flex"
                     v-for="item in content.items">
                    <div class="d-flex flex-column text-center justify-content-center mx-2">
                        <div class="widget__status-label h1">{{item.direction}}</div>
                        <div class="widget__status-data h2">{{item.speed}}
                            <small>km/h</small>
                        </div>
                        <div class="widget__status-data h4">{{item.location}}</div>
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
