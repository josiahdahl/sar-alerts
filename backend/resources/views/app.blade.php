<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script>
      window.pageData = {!! $data !!};
    </script>
</head>
<body>
<div id="app">
    <div class="container">
        <div class="row app-row" v-for="row in layout">
            <component v-for="(widget, idx) in row"
                       :key="idx"
                       :is="widget.widget"
                       :sizes="widget.sizes"
                       :data-sources="widget.dataSources">
            </component>
        </div>
    </div>
</div>
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
