<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="icon" type="image/png" href="images/logo.png">
    <title>{{ $title ?? 'Kisa' }}</title>

    @vite(['resources/css/app.css'])
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />


</head>

<body
    class="bg-slate-50 text-slate-900 antialiased font-sans min-h-screen flex flex-col justify-between p-3 max-w-md mx-auto">
    {{ $slot }}
    @include('partials.bottom-nav')

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

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
