<x-layouts.admin title="Staff" subtitle="Manage cashier and barista accounts">
    <div class="space-y-5">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="font-serif text-2xl text-espresso">Staff List</h2>
                <p class="text-xs text-brown-500">Create, assign roles, edit, disable, or delete staff accounts.</p>
            </div>
            <a
                href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center rounded-xl bg-espresso px-4 py-2.5 text-sm font-medium text-cream transition hover:bg-brown-800"
            >
                + New Staff
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-brown-100 bg-cream shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-brown-50 text-brown-600">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Name</th>
                            <th class="px-4 py-3 text-left font-semibold">Email</th>
                            <th class="px-4 py-3 text-left font-semibold">Role</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Created</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-100">
                        @forelse($staff as $member)
                            <tr class="bg-white/70">
                                <td class="px-4 py-3 font-medium text-espresso">{{ $member->name }}</td>
                                <td class="px-4 py-3 text-brown-600">{{ $member->email }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $member->role === 'cashier' ? 'border-blue-200 bg-blue-50 text-blue-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $member->is_active ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                                        {{ $member->is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-brown-500">{{ optional($member->created_at)->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.staff.edit', $member) }}"
                                            class="rounded-lg border border-brown-200 px-3 py-1.5 text-xs font-medium text-brown-700 hover:bg-brown-50"
                                        >
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.staff.toggle-active', $member) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="rounded-lg border px-3 py-1.5 text-xs font-medium {{ $member->is_active ? 'border-amber-300 text-amber-700 hover:bg-amber-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}"
                                            >
                                                {{ $member->is_active ? 'Disable' : 'Enable' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" onsubmit="return confirm('Delete this staff account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-brown-500">No staff accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-brown-100 bg-white px-4 py-3">
                {{ $staff->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
