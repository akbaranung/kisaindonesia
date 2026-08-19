<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/png" href="images/logo.png">
    <title>{{ $title ?? 'Kisa' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>

</head>

<body
    class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex flex-col justify-between max-w-md mx-auto">
    {{ $slot }}
    @livewireScripts


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>


</html>
