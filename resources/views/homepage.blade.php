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
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>
</head>

<body class="antialiased">
    <main
        class="w-full h-screen flex flex-col items-center justify-center bg-gradient-to-r from-emerald-400 to-green-600 background-animation">
        <img src="/img/logo.png" class="w-40 mx-auto mb-8" alt="logo" />
        <h1 class="text-4xl font-thin text-white">Welcome to the Admin Panel</h1>
        <a href="/admin/login"
            class="inline-block bg-gradient-to-br from-teal-300 to-teal-400 py-4 px-12 rounded-full text-lg text-white font-bold uppercase tracking-wide shadow-xs shadow-white hover:shadow-2xl active:shadow-xl transform hover:-translate-y-1 active:translate-y-0 transition duration-200 mt-8">Go
            to Admin Console</a>
    </main>

    <style>
        body {
          font-family: "Inter", sans-serif;
        }
      
        .background-animation {
          background-size: 400%;
      
          -webkit-animation: AnimationName 3s ease infinite;
          -moz-animation: AnimationName 3s ease infinite;
          animation: AnimationName 3s ease infinite;
        }
      
        @keyframes AnimationName {
          0%,
          100% {
            background-position: 0% 50%;
          }
          50% {
            background-position: 100% 50%;
          }
        }
      </style>
      
</body>

</html>
