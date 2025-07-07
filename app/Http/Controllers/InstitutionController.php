<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Inertia\Response;

class InstitutionController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        return Inertia::render('Institutions/Index', [
            'institutions' => Institution::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institutions',
            'logo_path' => 'nullable|string|max:255',
        ]);

        Institution::create($validated);

        return redirect()->route('institutions.index');
    }

    public function update(Request $request, Institution $institution): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name,'.$institution->id,
            'logo_path' => 'nullable|string|max:255',
        ]);

        $institution->update($validated);

        return redirect()->route('institutions.index');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $institution->delete();
        return redirect()->route('institutions.index');
    }
}
