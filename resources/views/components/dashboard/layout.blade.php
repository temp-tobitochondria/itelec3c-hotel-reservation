<x-layouts.dashboard title="Dashboard">
    <div class="min-h-screen">
        <div class="container-xl py-10">
            <div class="rounded-3xl border border-slate-200 bg-white overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <aside class="lg:col-span-3 border-b lg:border-b-0 lg:border-r border-slate-200 bg-slate-50/60 p-6">
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-2xl bg-slate-900 text-white grid place-items-center font-bold tracking-tight">
                                A
                            </div>
                            <div class="leading-tight">
                                <div class="font-semibold tracking-tight">Aurum Hotel</div>
                                <div class="text-[11px] text-slate-500 -mt-0.5">Dashboard</div>
                            </div>
                        </div>

                        <nav class="mt-6 space-y-1 text-sm">
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard') ? 'bg-white' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span>Dashboard</span>
                            </a>

                            @if (auth()->user()->role === 'Admin')
                                <a href="{{ route('dashboard.users') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.users') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg><span>Users</span></a>
                                <a href="{{ route('dashboard.logs') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.logs') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><span>Logs</span></a>
                                <a href="{{ route('dashboard.rooms') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.rooms') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><span>Rooms</span></a>
                                <a href="{{ route('dashboard.amenities') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.amenities') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg><span>Amenities</span></a>
                                <a href="{{ route('dashboard.reservations') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.reservations') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span>Reservations</span></a>
                                <a href="{{ route('dashboard.approvals') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.approvals') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Approvals</span></a>
                            @elseif (auth()->user()->role === 'Employee')
                                <a href="{{ route('dashboard.rooms') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.rooms') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><span>Rooms</span></a>
                                <a href="{{ route('dashboard.amenities') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.amenities') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg><span>Amenities</span></a>
                                <a href="{{ route('dashboard.reservations') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.reservations') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span>Reservations</span></a>
                                <a href="{{ route('dashboard.approvals') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.approvals') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Approvals</span></a>
                            @else
                                <a href="{{ route('dashboard.rooms') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.rooms') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg><span>Rooms</span></a>
                                <a href="{{ route('dashboard.my-reservations') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.my-reservations') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><span>My Reservations</span></a>
                                <a href="{{ route('dashboard.reserve') }}" class="flex items-center gap-2 rounded-2xl px-3 py-2 border border-slate-200 hover:bg-white {{ request()->routeIs('dashboard.reserve') ? 'bg-white' : '' }}"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>Reserve</span></a>
                            @endif
                        </nav>

                        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs text-slate-500">Signed in as</div>
                            <div class="mt-1 font-semibold">{{ auth()->user()->username }}</div>
                            <div class="mt-0.5 text-xs text-slate-500">{{ auth()->user()->role }}</div>
                        </div>

                        <div class="mt-4">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full btn btn-soft">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </aside>

                    <main class="lg:col-span-9 p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="relative w-full sm:max-w-md">
                                <input type="search" placeholder="Search..." aria-label="Search"
                                       class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-200" />
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ url('/') }}" class="btn btn-soft">Back to site</a>
                                <div class="h-10 w-10 rounded-full bg-slate-200"></div>
                                <div class="leading-tight">
                                    <div class="text-sm font-semibold">{{ auth()->user()->full_name }}</div>
                                    <div class="text-xs text-slate-500">{{ auth()->user()->role }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            {{ $slot }}
                        </div>

                        @if (session('success'))
                            <script>
                                window.addEventListener('DOMContentLoaded', () => {
                                    const show = () => {
                                        window.Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: @json(session('success')),
                                            timer: 1600,
                                            showConfirmButton: false,
                                        });
                                    };

                                    if (window.Swal) {
                                        show();
                                        return;
                                    }

                                    // Fallback: load SweetAlert2 from CDN (useful if Vite build/dev isn't running)
                                    const script = document.createElement('script');
                                    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                                    script.onload = () => {
                                        if (window.Swal) show();
                                    };
                                    document.head.appendChild(script);
                                });
                            </script>
                        @endif

                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                const ensureSwal = () => {
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

                                document.addEventListener('submit', async (e) => {
                                    const form = e.target;
                                    if (!(form instanceof HTMLFormElement)) return;
                                    if (!form.hasAttribute('data-swal-confirm')) return;

                                    e.preventDefault();

                                    const title = form.getAttribute('data-swal-title') || 'Are you sure?';
                                    const text = form.getAttribute('data-swal-text') || 'This action cannot be undone.';
                                    const confirmText = form.getAttribute('data-swal-confirm-text') || 'Yes, continue';
                                    const cancelText = form.getAttribute('data-swal-cancel-text') || 'Cancel';

                                    const Swal = await ensureSwal();
                                    if (!Swal) return;

                                    const result = await Swal.fire({
                                        icon: 'warning',
                                        title,
                                        text,
                                        showCancelButton: true,
                                        confirmButtonText: confirmText,
                                        cancelButtonText: cancelText,
                                        reverseButtons: true,
                                    });

                                    if (result.isConfirmed) {
                                        form.submit();
                                    }
                                }, true);
                            });
                        </script>
                    </main>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
