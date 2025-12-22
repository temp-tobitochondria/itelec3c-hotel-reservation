<x-dashboard.layout>
    <h1 class="text-2xl font-semibold tracking-tight">Logs</h1>
    <p class="mt-2 text-sm text-slate-600">Admin can view system/user logs.</p>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Date/Time</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                    <th class="px-4 py-3 text-left font-semibold">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50/60">
                        <td class="px-4 py-3 text-slate-700">{{ optional($log->date_time)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $log->user?->username ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $log->action }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-slate-500">No logs found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard.layout>
