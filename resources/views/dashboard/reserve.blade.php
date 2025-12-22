<x-dashboard.layout>
    <h1 class="text-2xl font-semibold tracking-tight">Reserve a Room</h1>
    <p class="mt-2 text-sm text-slate-600">Create a new room reservation.</p>

    @if (!empty($checkIn) && !empty($checkOut))
        <div class="mt-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
            Showing availability for <span class="font-semibold">{{ $checkIn }}</span> to <span class="font-semibold">{{ $checkOut }}</span>
            @if (!empty($guests))
                · Guests: <span class="font-semibold">{{ $guests }}</span>
            @endif
            @if (!empty($roomType) && strtolower($roomType) !== 'any')
                · Type: <span class="font-semibold">{{ $roomType }}</span>
            @endif
        </div>
    @else
        <div class="mt-4 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
            Select your dates on the home page first to reserve.
        </div>
    @endif

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="px-4 py-3 bg-slate-50 text-sm text-slate-600">Available rooms</div>
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-sm break-words">
                <thead class="bg-white text-slate-600">
                <tr class="border-b border-slate-200">
                    <th class="px-4 py-3 text-left font-semibold">Room #</th>
                    <th class="px-4 py-3 text-left font-semibold">Type</th>
                    <th class="px-4 py-3 text-left font-semibold">Capacity</th>
                    <th class="px-4 py-3 text-left font-semibold">Price / Night</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                    <th class="px-4 py-3 text-left font-semibold">Action</th>
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
                        <td class="px-4 py-3 text-slate-700">
                            @if (!empty($checkIn) && !empty($checkOut))
                                <button type="button"
                                        class="btn btn-primary"
                                        data-reserve-modal-open
                                        data-room-id="{{ $room->room_id }}"
                                        data-room-number="{{ $room->room_number }}"
                                        data-room-type="{{ $room->room_type }}">
                                    Reserve
                                </button>
                            @else
                                <span class="text-xs text-slate-500">Set dates first</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">No available rooms right now.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="reserveAmenityModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40" data-reserve-modal-close></div>
        <div class="absolute inset-0 grid place-items-center p-4">
            <div class="w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-lg font-semibold">Select amenities</div>
                        <div id="reserveAmenitySubtitle" class="mt-1 text-sm text-slate-600"></div>
                    </div>
                    <button type="button" class="btn btn-soft" data-reserve-modal-close>Close</button>
                </div>

                <form id="reserveAmenityForm" method="POST" action="{{ route('dashboard.reserve.store') }}"
                      class="mt-5"
                      data-swal-confirm
                      data-swal-title="Confirm reservation?"
                      data-swal-text="Your reservation will be submitted for approval."
                      data-swal-confirm-text="Yes, reserve">
                    @csrf
                    <input id="reserveRoomId" type="hidden" name="room_id" value="" />
                    <input type="hidden" name="check_in_date" value="{{ $checkIn ?? '' }}" />
                    <input type="hidden" name="check_out_date" value="{{ $checkOut ?? '' }}" />

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="text-sm font-semibold text-slate-900">Amenities (optional)</div>

                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @forelse (($amenities ?? collect()) as $amenity)
                                <label class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                    <span class="flex items-center gap-3">
                                        <input type="checkbox" name="amenity_ids[]" value="{{ $amenity->amenity_id }}" class="rounded border-slate-300" />
                                        <span class="text-slate-800">{{ $amenity->amenity_name }}</span>
                                    </span>
                                    <span class="text-slate-600">₱{{ number_format((float) $amenity->price_per_use, 2) }}</span>
                                </label>
                            @empty
                                <div class="text-sm text-slate-600">No amenities available.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button type="button" class="btn btn-soft" data-reserve-modal-close>Cancel</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('reserveAmenityModal');
            const roomIdInput = document.getElementById('reserveRoomId');
            const subtitle = document.getElementById('reserveAmenitySubtitle');

            function open({ roomId, roomNumber, roomType }) {
                roomIdInput.value = roomId;
                subtitle.textContent = `Room ${roomNumber} • ${roomType}`;
                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
            }

            document.querySelectorAll('[data-reserve-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    open({
                        roomId: btn.dataset.roomId,
                        roomNumber: btn.dataset.roomNumber,
                        roomType: btn.dataset.roomType,
                    });
                });
            });

            document.querySelectorAll('[data-reserve-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
