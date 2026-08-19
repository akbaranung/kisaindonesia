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

</head>

<body
    class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex flex-col justify-between p-3 max-w-md mx-auto">
    {{ $slot }}
    @include('partials.bottom-nav')
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (data) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#0f172a', // Slate 900
                    color: '#f8fafc',
                    customClass: {
                        popup: 'border border-slate-800 rounded-xl shadow-2xl'
                    }
                });

                Toast.fire({
                    icon: data.type || 'error',
                    title: data.message
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
