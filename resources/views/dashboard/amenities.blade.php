<x-dashboard.layout>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Amenities</h1>
            <p class="mt-2 text-sm text-slate-600">View amenities.</p>
        </div>
        <button
            type="button"
            class="btn btn-primary"
            data-amenity-modal-open
            data-mode="create"
            data-action="{{ route('dashboard.amenities.store') }}"
        >
            Add Amenity
        </button>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Amenity</th>
                    <th class="px-4 py-3 text-left font-semibold">Description</th>
                    <th class="px-4 py-3 text-left font-semibold">Price / Use</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($amenities as $amenity)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $amenity->amenity_name }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $amenity->description }}</td>
                        <td class="px-4 py-3 text-slate-700">₱{{ number_format((float) $amenity->price_per_use, 2) }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn-soft"
                                    data-amenity-modal-open
                                    data-mode="edit"
                                    data-action="{{ route('dashboard.amenities.update', $amenity->amenity_id) }}"
                                    data-amenity-name="{{ $amenity->amenity_name }}"
                                    data-price-per-use="{{ $amenity->price_per_use }}"
                                    data-description="{{ $amenity->description }}"
                                >
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('dashboard.amenities.destroy', $amenity->amenity_id) }}"
                                      data-swal-confirm
                                      data-swal-title="Delete amenity?"
                                      data-swal-text="This will permanently remove the amenity."
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
                        <td colspan="4" class="px-4 py-6 text-center text-slate-500">No amenities found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="amenityModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40" data-amenity-modal-close></div>
        <div class="absolute inset-0 grid place-items-center p-4">
            <div class="w-full max-w-2xl rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div id="amenityModalTitle" class="text-lg font-semibold">Add Amenity</div>
                        <div class="mt-1 text-sm text-slate-600">Fill in the details below.</div>
                    </div>
                    <button type="button" class="btn btn-soft" data-amenity-modal-close>Close</button>
                </div>

                <form id="amenityModalForm" method="POST" class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf
                    <input id="amenityModalMethod" type="hidden" name="_method" value="PATCH" disabled>

                    <input id="amenityName" name="amenity_name" placeholder="Amenity name" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="amenityPrice" name="price_per_use" type="number" step="0.01" min="0" placeholder="Price per use" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="amenityDescription" name="description" placeholder="Description" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm md:col-span-2" />

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('amenityModal');
            const form = document.getElementById('amenityModalForm');
            const method = document.getElementById('amenityModalMethod');
            const title = document.getElementById('amenityModalTitle');

            const name = document.getElementById('amenityName');
            const price = document.getElementById('amenityPrice');
            const description = document.getElementById('amenityDescription');

            function open({ mode, action, data }) {
                form.action = action;

                if (mode === 'edit') {
                    title.textContent = 'Edit Amenity';
                    method.disabled = false;
                    name.value = data.amenityName || '';
                    price.value = data.pricePerUse || '';
                    description.value = data.description || '';
                } else {
                    title.textContent = 'Add Amenity';
                    method.disabled = true;
                    name.value = '';
                    price.value = '';
                    description.value = '';
                }

                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
            }

            document.querySelectorAll('[data-amenity-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    open({
                        mode: btn.dataset.mode,
                        action: btn.dataset.action,
                        data: {
                            amenityName: btn.dataset.amenityName,
                            pricePerUse: btn.dataset.pricePerUse,
                            description: btn.dataset.description,
                        },
                    });
                });
            });

            document.querySelectorAll('[data-amenity-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
