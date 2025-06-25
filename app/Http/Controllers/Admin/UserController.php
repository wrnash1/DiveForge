<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DiveShop;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('primaryShop')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $shops = DiveShop::where('is_active', true)->get();
        return view('admin.users.create', compact('shops'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()],
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'primary_shop_id' => 'nullable|exists:dive_shops,id',
            'employment_status' => 'nullable|in:employee,contractor,volunteer,customer',
            'total_dives' => 'nullable|integer|min:0',
            'certification_level' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        $user->load('primaryShop');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $shops = DiveShop::where('is_active', true)->get();
        return view('admin.users.edit', compact('user', 'shops'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()],
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'phone' => 'nullable|string|max:20',
            'primary_shop_id' => 'nullable|exists:dive_shops,id',
            'employment_status' => 'nullable|in:employee,contractor,volunteer,customer',
            'total_dives' => 'nullable|integer|min:0',
            'certification_level' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
