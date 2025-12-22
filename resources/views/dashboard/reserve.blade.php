<x-dashboard.layout>
    <h1 class="text-2xl font-semibold tracking-tight">Reserve a Room</h1>
    <p class="mt-2 text-sm text-slate-600">Create a new room reservation.</p>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="px-4 py-3 bg-slate-50 text-sm text-slate-600">Available rooms</div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-white text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="px-4 py-3 text-left font-semibold">Room #</th>
                    <th class="px-4 py-3 text-left font-semibold">Type</th>
                    <th class="px-4 py-3 text-left font-semibold">Capacity</th>
                    <th class="px-4 py-3 text-left font-semibold">Price / Night</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($availableRooms as $room)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $room->room_number }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $room->room_type }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $room->capacity }}</td>
                        <td class="px-4 py-3 text-slate-700">₱{{ number_format((float) $room->price_per_night, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $room->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">No available rooms right now.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard.layout>
