<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title', 'KISA')</title>

    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        * {
            font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased font-sans pb-24">

    @yield('content')

    @include('partials.bottom-nav')

    @livewireScripts

</body>

</html>
