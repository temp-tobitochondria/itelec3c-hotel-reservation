<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Aurum Hotel' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/gallery/aurum-logo-only.svg') }}" />
    <link rel="icon" href="{{ asset('images/gallery/aurum-logo-only.svg') }}" />
</head>
<body class="bg-white text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        <x-navbar />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-footer />
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.ensureSwal = () => {
                if (window.Swal) return Promise.resolve(window.Swal);

                return new Promise((resolve) => {
                    const existing = document.querySelector('script[data-swal-cdn]');
                    if (existing) {
                        existing.addEventListener('load', () => resolve(window.Swal));
                        return;
                    }

                    const script = document.createElement('script');
                    script.dataset.swalCdn = '1';
                    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                    script.onload = () => resolve(window.Swal);
                    document.head.appendChild(script);
                });
            };
        });
    </script>

    @if (session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', async () => {
                if (!window.ensureSwal) return;
                const Swal = await window.ensureSwal();
                if (!Swal) return;

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: @json(session('success')),
                    timer: 1600,
                    showConfirmButton: false,
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', async () => {
                if (!window.ensureSwal) return;
                const Swal = await window.ensureSwal();
                if (!Swal) return;

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: @json(session('error')),
                });
            });
        </script>
    @endif
</body>
</html>
