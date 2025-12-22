<x-dashboard.layout>
    <h1 class="text-2xl font-semibold tracking-tight">My Reservations</h1>
    <p class="mt-2 text-sm text-slate-600">View your reservation status.</p>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-sm break-words">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Room</th>
                    <th class="px-4 py-3 text-left font-semibold">Check-in</th>
                    <th class="px-4 py-3 text-left font-semibold">Check-out</th>
                    <th class="px-4 py-3 text-left font-semibold">Amenities</th>
                    <th class="px-4 py-3 text-left font-semibold">Total</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($reservations as $reservation)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $reservation->room?->room_number ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ optional($reservation->check_in_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ optional($reservation->check_out_date)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            @if ($reservation->amenities->isNotEmpty())
                                {{ $reservation->amenities->pluck('amenity_name')->implode(', ') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-700">₱{{ number_format((float) $reservation->total_price, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $reservation->reservation_status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">No reservations yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $reservations->links() }}
    </div>
</x-dashboard.layout>
