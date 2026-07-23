<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the form for editing the system settings.
     */
    public function edit(): View
    {
        return view('settings.edit', [
            'setting' => Setting::current(),
        ]);
    }

    /**
     * Update the system settings in storage.
     */
    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $setting = Setting::current();
        $setting->update($request->validated());

        return redirect()->route('settings.edit')->with('status', 'Nastavenia systému boli úspešne upravené.');
    }
}
