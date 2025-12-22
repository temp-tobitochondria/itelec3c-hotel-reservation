<x-dashboard.layout>
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Users</h1>
            <p class="mt-2 text-sm text-slate-600">Admin can add, edit, and view users.</p>
        </div>
        <button
            type="button"
            class="btn btn-primary"
            data-user-modal-open
            data-mode="create"
            data-action="{{ route('dashboard.users.store') }}"
        >
            Add User
        </button>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-sm break-words">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Full Name</th>
                    <th class="px-4 py-3 text-left font-semibold">Username</th>
                    <th class="px-4 py-3 text-left font-semibold">Email</th>
                    <th class="px-4 py-3 text-left font-semibold">Role</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $user->full_name }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $user->username }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $user->role }}</td>
                        <td class="px-4 py-3 text-slate-700">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="btn btn-soft"
                                    data-user-modal-open
                                    data-mode="edit"
                                    data-action="{{ route('dashboard.users.update', $user->user_id) }}"
                                    data-full-name="{{ $user->full_name }}"
                                    data-username="{{ $user->username }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}"
                                >
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('dashboard.users.destroy', $user->user_id) }}"
                                      data-swal-confirm
                                      data-swal-title="Delete user?"
                                      data-swal-text="This will permanently remove the user."
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
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <div id="userModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40" data-user-modal-close></div>
        <div class="absolute inset-0 grid place-items-center p-4">
            <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div id="userModalTitle" class="text-lg font-semibold">Add User</div>
                        <div class="mt-1 text-sm text-slate-600">Fill in the details below.</div>
                    </div>
                    <button type="button" class="btn btn-soft" data-user-modal-close>Close</button>
                </div>

                <form id="userModalForm" method="POST" class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @csrf
                    <input id="userModalMethod" type="hidden" name="_method" value="PATCH" disabled>

                    <input id="userFullName" name="full_name" placeholder="Full name" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="userUsername" name="username" placeholder="Username" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="userEmail" type="email" name="email" placeholder="Email" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required />
                    <input id="userPassword" type="password" name="password" placeholder="Password" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" />
                    <select id="userRole" name="role" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm" required>
                        <option value="Admin">Admin</option>
                        <option value="Employee">Employee</option>
                        <option value="Customer">Customer</option>
                    </select>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userModalForm');
            const method = document.getElementById('userModalMethod');
            const title = document.getElementById('userModalTitle');

            const fullName = document.getElementById('userFullName');
            const username = document.getElementById('userUsername');
            const email = document.getElementById('userEmail');
            const password = document.getElementById('userPassword');
            const role = document.getElementById('userRole');

            function open({ mode, action, data }) {
                form.action = action;

                if (mode === 'edit') {
                    title.textContent = 'Edit User';
                    method.disabled = false;
                    password.required = false;
                    password.placeholder = 'New password (optional)';

                    fullName.value = data.fullName || '';
                    username.value = data.username || '';
                    email.value = data.email || '';
                    role.value = data.role || 'Customer';
                    password.value = '';
                } else {
                    title.textContent = 'Add User';
                    method.disabled = true;
                    password.required = true;
                    password.placeholder = 'Password';

                    fullName.value = '';
                    username.value = '';
                    email.value = '';
                    role.value = 'Customer';
                    password.value = '';
                }

                modal.classList.remove('hidden');
            }

            function close() {
                modal.classList.add('hidden');
            }

            document.querySelectorAll('[data-user-modal-open]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    open({
                        mode: btn.dataset.mode,
                        action: btn.dataset.action,
                        data: {
                            fullName: btn.dataset.fullName,
                            username: btn.dataset.username,
                            email: btn.dataset.email,
                            role: btn.dataset.role,
                        },
                    });
                });
            });

            document.querySelectorAll('[data-user-modal-close]').forEach((btn) => {
                btn.addEventListener('click', close);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        })();
    </script>
</x-dashboard.layout>
