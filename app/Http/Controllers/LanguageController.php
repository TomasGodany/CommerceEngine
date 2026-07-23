<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LanguageController extends Controller
{
    /**
     * Display a listing of the languages.
     */
    public function index(): View
    {
        return view('languages.index', [
            'languages' => Language::orderBy('code')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new language.
     */
    public function create(): View
    {
        return view('languages.create');
    }

    /**
     * Store a newly created language in storage.
     */
    public function store(StoreLanguageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        Language::create($validated);

        return redirect()->route('languages.index')->with('status', 'Jazyk bol úspešne vytvorený.');
    }

    /**
     * Show the form for editing the specified language.
     */
    public function edit(Language $language): View
    {
        return view('languages.edit', [
            'language' => $language,
        ]);
    }

    /**
     * Update the specified language in storage.
     */
    public function update(UpdateLanguageRequest $request, Language $language): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_default'] = $request->boolean('is_default');

        $language->update($validated);

        return redirect()->route('languages.index')->with('status', 'Jazyk bol úspešne upravený.');
    }

    /**
     * Remove the specified language from storage.
     */
    public function destroy(Language $language): RedirectResponse
    {
        $language->delete();

        return redirect()->route('languages.index')->with('status', 'Jazyk bol úspešne odstránený.');
    }
}
