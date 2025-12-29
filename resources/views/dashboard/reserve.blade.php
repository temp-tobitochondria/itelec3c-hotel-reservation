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
                                        data-room-type="{{ $room->room_type }}"
                                        data-room-price="{{ (float) $room->price_per_night }}">
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
            <div class="w-full max-w-2xl rounded-3xl border border-slate-200 bg-white">
                <form id="reserveAmenityForm" method="POST" action="{{ route('dashboard.reserve.store') }}" enctype="multipart/form-data"
                      class="flex max-h-[90vh] flex-col"
                      data-swal-confirm
                      data-swal-title="Confirm reservation?"
                      data-swal-text="Your reservation will be submitted for approval."
                      data-swal-confirm-text="Yes, reserve">
                    @csrf
                    <input id="reserveRoomId" type="hidden" name="room_id" value="" />
                    <input type="hidden" name="check_in_date" value="{{ $checkIn ?? '' }}" />
                    <input type="hidden" name="check_out_date" value="{{ $checkOut ?? '' }}" />

                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5">
                        <div>
                            <div id="reserveStepTitle" class="text-lg font-semibold">Select amenities</div>
                            <div id="reserveAmenitySubtitle" class="mt-1 text-sm text-slate-600"></div>
                            <div class="mt-2 text-sm text-slate-700">
                                <span class="font-semibold">Total:</span>
                                <span id="reserveTotalAmount" class="font-semibold">₱0.00</span>
                                <span id="reserveTotalMeta" class="ml-2 text-xs text-slate-500"></span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-soft" data-reserve-modal-close>Close</button>
                    </div>

                    <div class="flex-1 overflow-y-auto px-6 py-5">
                        <div id="reserveStep1" data-step="1">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-sm font-semibold text-slate-900">Amenities (optional)</div>
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @forelse (($amenities ?? collect()) as $amenity)
                                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                            <input type="checkbox" name="amenity_ids[]" value="{{ $amenity->amenity_id }}" data-amenity-price="{{ (float) $amenity->price_per_use }}" class="h-4 w-4 rounded border-slate-300" />
                                            <span class="flex-1 text-center text-slate-800">{{ $amenity->amenity_name }}</span>
                                            <span class="shrink-0 text-slate-600">₱{{ number_format((float) $amenity->price_per_use, 2) }}</span>
                                        </label>
                                    @empty
                                        <div class="text-sm text-slate-600">No amenities available.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div id="reserveStep2" data-step="2" class="hidden">
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="text-sm font-semibold text-slate-900">Payment</div>
                                <div class="mt-1 text-sm text-slate-600">Upload your payment receipt (PNG/JPG only, max 5MB).</div>

                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-3">
                                        @php($qrRelativePath = 'images/gallery/aurum-qr-gcash.jpg')
                                        @if (file_exists(public_path($qrRelativePath)))
                                            <img
                                                src="{{ asset($qrRelativePath) }}"
                                                alt="GCash QR Code"
                                                class="w-full max-w-xs rounded-2xl border border-slate-200"
                                            />
                                        @else
                                            <div class="text-sm text-slate-600">
                                                Missing QR image: <span class="font-medium">public/{{ $qrRelativePath }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-white p-3">
                                        <div class="text-sm text-slate-700">
                                            <span class="font-semibold">Amount due:</span>
                                            <span id="reserveTotalAmountDue" class="font-semibold">₱0.00</span>
                                        </div>

                                        <label class="mt-3 block text-sm font-semibold text-slate-900" for="payment_receipt">Upload receipt</label>
                                        <input
                                            id="payment_receipt"
                                            name="payment_receipt"
                                            type="file"
                                            accept="image/png,image/jpeg"
                                            required
                                            class="mt-2 block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm"
                                        />
                                        <div id="reserveReceiptHelp" class="mt-2 text-xs text-slate-600">Accepted: PNG, JPG (max 5MB)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-6 py-5">
                        <button id="reserveCancelBtn" type="button" class="btn btn-soft" data-reserve-modal-close>Cancel</button>

                        <button id="reserveBackBtn" type="button" class="btn btn-soft hidden">Back</button>
                        <button id="reserveNextBtn" type="button" class="btn btn-primary">Next</button>

                        <button id="reserveConfirmBtn" type="submit" class="btn btn-primary hidden">Confirm</button>
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
            const stepTitle = document.getElementById('reserveStepTitle');
            const totalEl = document.getElementById('reserveTotalAmount');
            const totalMetaEl = document.getElementById('reserveTotalMeta');
            const totalDueEl = document.getElementById('reserveTotalAmountDue');
            const form = document.getElementById('reserveAmenityForm');
            const step1El = document.getElementById('reserveStep1');
            const step2El = document.getElementById('reserveStep2');
            const nextBtn = document.getElementById('reserveNextBtn');
            const backBtn = document.getElementById('reserveBackBtn');
            const confirmBtn = document.getElementById('reserveConfirmBtn');
            const receiptInput = document.getElementById('payment_receipt');
            const receiptHelp = document.getElementById('reserveReceiptHelp');

            const checkIn = @json($checkIn ?? null);
            const checkOut = @json($checkOut ?? null);

            let currentRoomNightly = 0;
            let currentStep = 1;

            const setStep = (step) => {
                currentStep = step === 2 ? 2 : 1;

                if (stepTitle) stepTitle.textContent = currentStep === 1 ? 'Select amenities' : 'Payment';

                if (step1El) step1El.classList.toggle('hidden', currentStep !== 1);
                if (step2El) step2El.classList.toggle('hidden', currentStep !== 2);

                if (nextBtn) nextBtn.classList.toggle('hidden', currentStep !== 1);
                if (backBtn) backBtn.classList.toggle('hidden', currentStep !== 2);
                if (confirmBtn) confirmBtn.classList.toggle('hidden', currentStep !== 2);

                updateTotal();
            };

            const parseDate = (value) => {
                if (!value) return null;
                const d = new Date(`${value}T00:00:00`);
                return Number.isNaN(d.getTime()) ? null : d;
            };

            const nightsBetween = (startStr, endStr) => {
                const start = parseDate(startStr);
                const end = parseDate(endStr);
                if (!start || !end) return 0;
                const diff = Math.round((end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24));
                return Math.max(0, diff);
            };

            const formatPeso = (amount) => {
                const value = Number(amount) || 0;
                try {
                    return new Intl.NumberFormat('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(value);
                } catch (e) {
                    return `₱${value.toFixed(2)}`;
                }
            };

            const sumSelectedAmenities = () => {
                if (!form) return 0;
                let sum = 0;
                form.querySelectorAll('input[type="checkbox"][name="amenity_ids[]"]:checked').forEach((cb) => {
                    const price = parseFloat(cb.dataset.amenityPrice || '0');
                    if (!Number.isNaN(price)) sum += price;
                });
                return sum;
            };

            const updateTotal = () => {
                if (!totalEl || !totalMetaEl) return;

                const nights = nightsBetween(checkIn, checkOut);
                const effectiveNights = Math.max(1, nights || 0);
                const roomTotal = (Number(currentRoomNightly) || 0) * effectiveNights;
                const amenitiesTotal = sumSelectedAmenities();
                const total = roomTotal + amenitiesTotal;

                totalEl.textContent = formatPeso(total);
                if (totalDueEl) totalDueEl.textContent = formatPeso(total);

                const nightsText = nights > 0 ? `${effectiveNights} night${effectiveNights === 1 ? '' : 's'}` : 'per night';
                totalMetaEl.textContent = `${formatPeso(roomTotal)} room • ${formatPeso(amenitiesTotal)} amenities • ${nightsText}`;
            };

            function open({ roomId, roomNumber, roomType }) {
                roomIdInput.value = roomId;
                subtitle.textContent = `Room ${roomNumber} • ${roomType}`;
                modal.classList.remove('hidden');
                setStep(1);
                updateTotal();
            }

            function close() {
                modal.classList.add('hidden');
            }

            const validateReceiptSize = () => {
                if (!receiptInput || !receiptHelp) return true;
                if (!receiptInput.files || receiptInput.files.length === 0) {
                    receiptHelp.textContent = 'Accepted: PNG, JPG (max 5MB)';
                    receiptHelp.classList.remove('text-red-600');
                    receiptHelp.classList.add('text-slate-600');
                    return true;
                }
                const file = receiptInput.files[0];
                const maxBytes = 5 * 1024 * 1024;
                if (file.size > maxBytes) {
                    receiptHelp.textContent = 'File too large. Max size is 5MB.';
                    receiptHelp.classList.remove('text-slate-600');
                    receiptHelp.classList.add('text-red-600');
                    return false;
                }
                receiptHelp.textContent = 'Accepted: PNG, JPG (max 5MB)';
                receiptHelp.classList.remove('text-red-600');
                receiptHelp.classList.add('text-slate-600');
                return true;
            };

            document.querySelectorAll('[data-reserve-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    currentRoomNightly = parseFloat(btn.dataset.roomPrice || '0') || 0;
                    open({
                        roomId: btn.dataset.roomId,
                        roomNumber: btn.dataset.roomNumber,
                        roomType: btn.dataset.roomType,
                    });
                });
            });

            if (form) {
                form.addEventListener('change', (e) => {
                    const target = e.target;
                    if (target && target.matches('input[type="checkbox"][name="amenity_ids[]"]')) {
                        updateTotal();
                    }
                    if (target && target.id === 'payment_receipt') {
                        validateReceiptSize();
                    }
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    setStep(2);
                });
            }

            if (backBtn) {
                backBtn.addEventListener('click', () => {
                    setStep(1);
                });
            }

            if (form) {
                form.addEventListener('submit', (e) => {
                    if (currentStep !== 2) {
                        e.preventDefault();
                        setStep(2);
                        return;
                    }
                    if (!validateReceiptSize()) {
                        e.preventDefault();
                    }
                });
            }

            document.querySelectorAll('[data-reserve-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
