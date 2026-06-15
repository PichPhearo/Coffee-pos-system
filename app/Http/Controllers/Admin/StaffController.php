<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $staff = User::query()
            ->whereIn('role', ['cashier', 'barista'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:cashier,barista'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'],
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff account created.');
    }

    public function edit(User $staff): View
    {
        $this->ensureManageable($staff);

        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        $this->ensureManageable($staff);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'role' => ['required', 'in:cashier,barista'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->role = $validated['role'];
        $staff->is_active = (bool) ($validated['is_active'] ?? false);

        if (! empty($validated['password'])) {
            $staff->password = $validated['password'];
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'Staff account updated.');
    }

    public function toggleActive(User $staff): RedirectResponse
    {
        $this->ensureManageable($staff);

        $staff->is_active = ! (bool) $staff->is_active;
        $staff->save();

        $message = $staff->is_active ? 'Staff account enabled.' : 'Staff account disabled.';

        return redirect()->route('admin.staff.index')->with('success', $message);
    }

    public function destroy(User $staff): RedirectResponse
    {
        $this->ensureManageable($staff);

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff account deleted.');
    }

    private function ensureManageable(User $staff): void
    {
        abort_unless(in_array($staff->role, ['cashier', 'barista'], true), 404);
    }
}
