<x-dashboard.layout>
    @php
        $role = auth()->user()->role;
    @endphp

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-600">Overview and quick actions.</p>
        </div>
    </div>

    @if ($role === 'Admin' || $role === 'Employee')
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-emerald-50 p-5">
                <div class="text-xs text-slate-600">New Bookings</div>
                <div class="mt-2 text-2xl font-semibold">{{ number_format($totalReservations) }}</div>
                <div class="mt-1 text-xs text-slate-600">All-time reservations</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="text-xs text-slate-600">Check-in Today</div>
                <div class="mt-2 text-2xl font-semibold">{{ number_format($todayCheckIns) }}</div>
                <div class="mt-1 text-xs text-slate-600">{{ $today }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="text-xs text-slate-600">Check-out Today</div>
                <div class="mt-2 text-2xl font-semibold">{{ number_format($todayCheckOuts) }}</div>
                <div class="mt-1 text-xs text-slate-600">{{ $today }}</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="text-xs text-slate-600">Total Revenue</div>
                <div class="mt-2 text-2xl font-semibold">₱{{ number_format($totalRevenue, 2) }}</div>
                <div class="mt-1 text-xs text-slate-600">Based on reservations</div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 xl:col-span-2">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Revenue</div>
                    <div class="text-xs text-slate-500">Placeholder</div>
                </div>
                <div class="mt-4 h-40 rounded-2xl bg-slate-50 border border-slate-200"></div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <div class="font-semibold">Room Availability</div>
                    <div class="text-xs text-slate-500">Current</div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs text-slate-600">Available</div>
                        <div class="mt-1 text-xl font-semibold">{{ number_format($roomsAvailable) }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-xs text-slate-600">Unavailable</div>
                        <div class="mt-1 text-xl font-semibold">{{ number_format($roomsUnavailable) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-emerald-50 p-5">
                <div class="text-xs text-slate-600">Available Rooms</div>
                <div class="mt-2 text-2xl font-semibold">{{ number_format($roomsAvailable) }}</div>
                <div class="mt-1 text-xs text-slate-600">Ready to book</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="text-xs text-slate-600">My Reservations</div>
                <div class="mt-2 text-2xl font-semibold">{{ number_format($myReservations) }}</div>
                <div class="mt-1 text-xs text-slate-600">Track status anytime</div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="text-xs text-slate-600">Quick Action</div>
                <div class="mt-4">
                    <a href="{{ route('dashboard.reserve') }}" class="btn btn-primary w-full">Reserve a Room</a>
                </div>
            </div>
        </div>
    @endif
</x-dashboard.layout>
