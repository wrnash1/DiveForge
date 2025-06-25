<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiveShop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DiveShopController extends Controller
{
    public function index(): View
    {
        $shops = DiveShop::with('owner')
            ->latest()
            ->paginate(15);

        return view('admin.shops.index', compact('shops'));
    }

    public function create(): View
    {
        $users = User::where('is_active', true)->get();
        return view('admin.shops.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dive_shops,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'timezone' => 'required|string|max:50',
            'currency' => 'required|string|size:3',
            'owner_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        DiveShop::create($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Dive shop created successfully.');
    }

    public function show(DiveShop $shop): View
    {
        $shop->load('owner');
        return view('admin.shops.show', compact('shop'));
    }

    public function edit(DiveShop $shop): View
    {
        $users = User::where('is_active', true)->get();
        return view('admin.shops.edit', compact('shop', 'users'));
    }

    public function update(Request $request, DiveShop $shop): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:dive_shops,email,' . $shop->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'timezone' => 'required|string|max:50',
            'currency' => 'required|string|size:3',
            'owner_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $shop->update($validated);

        return redirect()->route('admin.shops.index')
            ->with('success', 'Dive shop updated successfully.');
    }

    public function destroy(DiveShop $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('admin.shops.index')
            ->with('success', 'Dive shop deleted successfully.');
    }
}
