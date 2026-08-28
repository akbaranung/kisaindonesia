<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles

</head>

<body class="bg-slate-950 text-slate-100 antialiased font-sans" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        @include('partials.admin.sidebar')

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">

            @include('partials.admin.header')

            <main class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>

    @livewireScripts
</body>

</html>
