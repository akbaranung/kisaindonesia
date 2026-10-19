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


    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between max-w-md mx-auto transition-colors duration-200 overscroll-y-none">
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

    @php
        $isDuitkuSandbox = filter_var(
            \App\Models\Setting::get('duitku_is_sandbox', config('services.duitku.sandbox', true)),
            FILTER_VALIDATE_BOOLEAN
        );
    @endphp
    <script
        src="{{ $isDuitkuSandbox ? 'https://app-sandbox.duitku.com/lib/js/duitku.js' : 'https://app-prod.duitku.com/lib/js/duitku.js' }}">
    </script>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-duitku-popup', (data) => {
                const responseData = Array.isArray(data) ? data[0] : data;
                const duitkuReference = responseData.reference; // Menggunakan reference Duitku
                const merchantOrderId = responseData.referenceCode;

                if (!duitkuReference) {
                    console.error('Reference Duitku tidak ditemukan!');
                    return;
                }

                // Panggil SDK Duitku dengan Reference Code
                checkout.process(duitkuReference, {
                    successEvent: function(result) {
                        window.location.href = "{{ route('topup.return') }}?merchantOrderId=" +
                            merchantOrderId;
                    },
                    pendingEvent: function(result) {
                        window.location.href = "{{ route('topup.return') }}?merchantOrderId=" +
                            merchantOrderId;
                    },
                    errorEvent: function(result) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Pembayaran gagal.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href =
                                "{{ route('topup.return') }}?merchantOrderId=" +
                                merchantOrderId;
                        });
                    },
                    closeEvent: function(result) {
                        window.location.href = "{{ route('topup.return') }}?merchantOrderId=" +
                            merchantOrderId;
                    }
                });
            });
        });
    </script>
    <script>
        function getTheme() {
            return localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        }

        function syncThemeState() {
            const currentTheme = getTheme();
            if (currentTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function setTheme(mode) {
            if (mode === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else if (mode === 'light') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                localStorage.removeItem('theme');
                syncThemeState();
            }
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: mode } }));
        }

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            const next = isDark ? 'light' : 'dark';
            setTheme(next);
            return next;
        }

        // Livewire v3 SPA Navigation support (wire:navigate)
        document.addEventListener('livewire:navigated', () => {
            syncThemeState();
        });
    </script>
    @stack('scripts')
</body>

</html>
