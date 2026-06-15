<x-layouts.admin title="Edit Staff" subtitle="Update staff account and role">
    <div class="max-w-2xl">
        <div class="mb-4">
            <a href="{{ route('admin.staff.index') }}" class="text-sm text-brown-600 hover:text-espresso">← Back to staff list</a>
        </div>

        <div class="rounded-2xl border border-brown-100 bg-cream p-5 shadow-sm">
            <h2 class="font-serif text-xl text-espresso">Edit Staff Account</h2>
            <p class="mt-1 text-xs text-brown-500">Update profile, role, status, or reset password.</p>

            <form method="POST" action="{{ route('admin.staff.update', $staff) }}" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-brown-600" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $staff->name) }}" required class="w-full rounded-xl border border-brown-200 px-3 py-2.5 text-sm focus:border-crema focus:outline-none">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-brown-600" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $staff->email) }}" required class="w-full rounded-xl border border-brown-200 px-3 py-2.5 text-sm focus:border-crema focus:outline-none">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-brown-600" for="role">Role</label>
                    <select id="role" name="role" required class="w-full rounded-xl border border-brown-200 px-3 py-2.5 text-sm focus:border-crema focus:outline-none">
                        <option value="cashier" @selected(old('role', $staff->role) === 'cashier')>Cashier</option>
                        <option value="barista" @selected(old('role', $staff->role) === 'barista')>Barista</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-brown-600" for="password">New Password (optional)</label>
                        <input id="password" name="password" type="password" class="w-full rounded-xl border border-brown-200 px-3 py-2.5 text-sm focus:border-crema focus:outline-none">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-brown-600" for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-brown-200 px-3 py-2.5 text-sm focus:border-crema focus:outline-none">
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-brown-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $staff->is_active) == 1) class="rounded border-brown-300 text-espresso focus:ring-crema">
                    Active account
                </label>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.staff.index') }}" class="rounded-xl border border-brown-200 px-4 py-2 text-sm text-brown-700 hover:bg-brown-50">Cancel</a>
                    <button type="submit" class="rounded-xl bg-espresso px-4 py-2 text-sm font-medium text-cream hover:bg-brown-800">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
