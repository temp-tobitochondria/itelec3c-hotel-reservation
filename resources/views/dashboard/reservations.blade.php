<x-dashboard.layout>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Reservations</h1>
            <p class="mt-2 text-sm text-slate-600">View reservations.</p>
        </div>
        <button
            type="button"
            class="btn btn-primary"
            data-reservation-modal-open
            data-mode="create"
            data-action="{{ route('dashboard.reservations.store') }}"
        >
            Add Reservation
        </button>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-sm break-words">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Customer</th>
                    <th class="px-4 py-3 text-left font-semibold">Room</th>
                    <th class="px-4 py-3 text-left font-semibold">Check-in</th>
                    <th class="px-4 py-3 text-left font-semibold">Check-out</th>
                    <th class="px-4 py-3 text-left font-semibold">Amenities</th>
                    <th class="px-4 py-3 text-left font-semibold">Total</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($reservations as $reservation)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $reservation->user?->full_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $reservation->room?->room_number ?? '—' }}</td>
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
                        <td class="px-4 py-3 text-slate-700">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn-soft"
                                    data-reservation-modal-open
                                    data-mode="edit"
                                    data-action="{{ route('dashboard.reservations.update', $reservation->reservation_id) }}"
                                    data-user-id="{{ $reservation->user_id }}"
                                    data-room-id="{{ $reservation->room_id }}"
                                    data-status="{{ strtolower($reservation->reservation_status) }}"
                                    data-check-in="{{ optional($reservation->check_in_date)->format('Y-m-d') }}"
                                    data-check-out="{{ optional($reservation->check_out_date)->format('Y-m-d') }}"
                                >
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('dashboard.reservations.destroy', $reservation->reservation_id) }}"
                                      data-swal-confirm
                                      data-swal-title="Delete reservation?"
                                      data-swal-text="This will permanently remove the reservation."
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
                        <td colspan="8" class="px-4 py-6 text-center text-slate-500">No reservations found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $reservations->links() }}
    </div>

    <div id="reservationModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40" data-reservation-modal-close></div>
        <div class="absolute inset-0 grid place-items-center p-4">
            <div class="w-full max-w-3xl rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div id="reservationModalTitle" class="text-lg font-semibold">Add Reservation</div>
                        <div class="mt-1 text-sm text-slate-600">Fill in the details below.</div>
                    </div>
                    <button type="button" class="btn btn-soft" data-reservation-modal-close>Close</button>
                </div>

                <form id="reservationModalForm" method="POST" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input id="reservationModalMethod" type="hidden" name="_method" value="PATCH" disabled>

                    <select id="reservationUser" name="user_id" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                        <option value="">Select user</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->user_id }}">{{ $u->full_name }} ({{ $u->username }})</option>
                        @endforeach
                    </select>

                    <select id="reservationRoom" name="room_id" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                        <option value="">Select room</option>
                        @foreach ($rooms as $r)
                            <option value="{{ $r->room_id }}">{{ $r->room_number }} - {{ $r->room_type }}</option>
                        @endforeach
                    </select>

                    <select id="reservationStatus" name="reservation_status" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                        <option value="pending">pending</option>
                        <option value="approved">approved</option>
                        <option value="rejected">rejected</option>
                    </select>

                    <input id="reservationCheckIn" type="date" name="check_in_date" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="reservationCheckOut" type="date" name="check_out_date" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('reservationModal');
            const form = document.getElementById('reservationModalForm');
            const method = document.getElementById('reservationModalMethod');
            const title = document.getElementById('reservationModalTitle');

            const user = document.getElementById('reservationUser');
            const room = document.getElementById('reservationRoom');
            const status = document.getElementById('reservationStatus');
            const checkIn = document.getElementById('reservationCheckIn');
            const checkOut = document.getElementById('reservationCheckOut');

            function open({ mode, action, data }) {
                form.action = action;

                if (mode === 'edit') {
                    title.textContent = 'Edit Reservation';
                    method.disabled = false;
                    user.value = data.userId || '';
                    room.value = data.roomId || '';
                    status.value = data.status || 'pending';
                    checkIn.value = data.checkIn || '';
                    checkOut.value = data.checkOut || '';
                } else {
                    title.textContent = 'Add Reservation';
                    method.disabled = true;
                    user.value = '';
                    room.value = '';
                    status.value = 'pending';
                    checkIn.value = '';
                    checkOut.value = '';
                }

                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
            }

            document.querySelectorAll('[data-reservation-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    open({
                        mode: btn.dataset.mode,
                        action: btn.dataset.action,
                        data: {
                            userId: btn.dataset.userId,
                            roomId: btn.dataset.roomId,
                            status: btn.dataset.status,
                            checkIn: btn.dataset.checkIn,
                            checkOut: btn.dataset.checkOut,
                        },
                    });
                });
            });

            document.querySelectorAll('[data-reservation-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
