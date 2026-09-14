<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

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
</body>

</html>
