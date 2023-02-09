<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ env('APP_NAME') }}</title>
        <link rel="icon" 
          type="image/png" 
          href="/img/icon.png">
        <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
        @vite(['resources/js/app.js', 'resources/css/app.css'])
    </head>
    <body class="antialiased">
        <div id="app">
            <app-component></app-component>
        </div>
    </body>
</html>
