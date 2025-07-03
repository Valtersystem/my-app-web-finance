<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // 1. ADICIONE ESTA LINHA
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    use AuthorizesRequests; // 2. ADICIONE ESTA LINHA

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $accounts = $user->accounts()->get();

        return Inertia::render('Accounts/Index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'initial_balance' => 'required|numeric',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'include_in_dashboard' => 'required|boolean',
        ]);

        $request->user()->accounts()->create($validated);

        return redirect()->route('accounts.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account): RedirectResponse
    {
        $this->authorize('update', $account); // Agora o erro deve desaparecer

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'include_in_dashboard' => 'required|boolean',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account): RedirectResponse
    {
        $this->authorize('delete', $account); // E aqui também

        $account->delete();

        return redirect()->route('accounts.index');
    }
}
