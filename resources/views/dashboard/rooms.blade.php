<x-dashboard.layout>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Rooms</h1>
            <p class="mt-2 text-sm text-slate-600">View rooms and their availability.</p>
        </div>
        <button
            type="button"
            class="btn btn-primary"
            data-room-modal-open
            data-mode="create"
            data-action="{{ route('dashboard.rooms.store') }}"
        >
            Add Room
        </button>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Room #</th>
                    <th class="px-4 py-3 text-left font-semibold">Type</th>
                    <th class="px-4 py-3 text-left font-semibold">Capacity</th>
                    <th class="px-4 py-3 text-left font-semibold">Price / Night</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($rooms as $room)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $room->room_number }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $room->room_type }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $room->capacity }}</td>
                        <td class="px-4 py-3 text-slate-700">₱{{ number_format((float) $room->price_per_night, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ ucfirst($room->availability_status) }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn-soft"
                                    data-room-modal-open
                                    data-mode="edit"
                                    data-action="{{ route('dashboard.rooms.update', $room->room_id) }}"
                                    data-room-number="{{ $room->room_number }}"
                                    data-room-type="{{ $room->room_type }}"
                                    data-capacity="{{ $room->capacity }}"
                                    data-price-per-night="{{ $room->price_per_night }}"
                                    data-availability-status="{{ $room->availability_status }}"
                                    data-description="{{ $room->description }}"
                                >
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('dashboard.rooms.destroy', $room->room_id) }}"
                                      data-swal-confirm
                                      data-swal-title="Delete room?"
                                      data-swal-text="This will permanently remove the room."
                                      data-swal-confirm-text="Yes, delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">No rooms found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="roomModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40" data-room-modal-close></div>
        <div class="absolute inset-0 grid place-items-center p-4">
            <div class="w-full max-w-3xl rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div id="roomModalTitle" class="text-lg font-semibold">Add Room</div>
                        <div class="mt-1 text-sm text-slate-600">Fill in the details below.</div>
                    </div>
                    <button type="button" class="btn btn-soft" data-room-modal-close>Close</button>
                </div>

                <form id="roomModalForm" method="POST" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input id="roomModalMethod" type="hidden" name="_method" value="PATCH" disabled>

                    <input id="roomNumber" name="room_number" placeholder="Room #" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="roomType" name="room_type" placeholder="Room type" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="roomCapacity" name="capacity" type="number" min="1" placeholder="Capacity" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="roomPrice" name="price_per_night" type="number" step="0.01" min="0" placeholder="Price per night" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <select id="roomStatus" name="availability_status" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                        <option value="available">available</option>
                        <option value="unavailable">unavailable</option>
                    </select>
                    <input id="roomDescription" name="description" placeholder="Description (optional)" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm md:col-span-2" />

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('roomModal');
            const form = document.getElementById('roomModalForm');
            const method = document.getElementById('roomModalMethod');
            const title = document.getElementById('roomModalTitle');

            const roomNumber = document.getElementById('roomNumber');
            const roomType = document.getElementById('roomType');
            const capacity = document.getElementById('roomCapacity');
            const price = document.getElementById('roomPrice');
            const status = document.getElementById('roomStatus');
            const description = document.getElementById('roomDescription');

            function open({ mode, action, data }) {
                form.action = action;

                if (mode === 'edit') {
                    title.textContent = 'Edit Room';
                    method.disabled = false;
                    roomNumber.value = data.roomNumber || '';
                    roomType.value = data.roomType || '';
                    capacity.value = data.capacity || '';
                    price.value = data.pricePerNight || '';
                    status.value = data.availabilityStatus || 'available';
                    description.value = data.description || '';
                } else {
                    title.textContent = 'Add Room';
                    method.disabled = true;
                    roomNumber.value = '';
                    roomType.value = '';
                    capacity.value = '';
                    price.value = '';
                    status.value = 'available';
                    description.value = '';
                }

                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
            }

            document.querySelectorAll('[data-room-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    open({
                        mode: btn.dataset.mode,
                        action: btn.dataset.action,
                        data: {
                            roomNumber: btn.dataset.roomNumber,
                            roomType: btn.dataset.roomType,
                            capacity: btn.dataset.capacity,
                            pricePerNight: btn.dataset.pricePerNight,
                            availabilityStatus: btn.dataset.availabilityStatus,
                            description: btn.dataset.description,
                        },
                    });
                });
            });

            document.querySelectorAll('[data-room-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
