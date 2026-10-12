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
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-image-resize-module@3.0.0/image-resize.min.js"></script>

    {{-- Script untuk Mencegah Flash / Kedipan saat halaman dimuat berdasarkan localStorage --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body
    class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased font-sans min-h-screen flex flex-col justify-between max-w-md mx-auto transition-colors duration-200">
    {{ $slot }}
    @livewireScripts


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    {{-- Fungsi Global untuk Toggle Theme / Mode Gelap & Terang --}}
    <script>
        function toggleThemeAndUpdateUI() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateThemeUI();
        }

        function updateThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const iconLabel = document.getElementById('theme-icon-label');
            const switchBtn = document.getElementById('theme-switch-btn');

            if (iconLabel) {
                if (isDark) {
                    iconLabel.classList.remove('fa-moon', 'text-slate-400');
                    iconLabel.classList.add('fa-sun', 'text-amber-400');
                } else {
                    iconLabel.classList.remove('fa-sun', 'text-amber-400');
                    iconLabel.classList.add('fa-moon', 'text-slate-400');
                }
            }

            if (switchBtn) {
                switchBtn.setAttribute('aria-checked', isDark);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateThemeUI();
        });
    </script>
</body>

</html>
