<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationSettingsController extends Controller
{
    private function getOrg()
    {
        return app('currentOrganization');
    }

    public function edit()
    {
        $org = $this->getOrg();

        if (auth()->id() !== $org->owner_id) {
            abort(403, 'Only the organization owner can access settings.');
        }

        return view('landlord.organization.settings', compact('org'));
    }

    public function update(Request $request)
    {
        $org = $this->getOrg();

        if (auth()->id() !== $org->owner_id) {
            abort(403, 'Only the organization owner can access settings.');
        }

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color'  => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo'          => ['nullable', 'image', 'max:2048'],
            'timezone'      => ['nullable', 'string', 'max:50'],
            'currency'      => ['nullable', 'string', 'max:10'],
        ]);

        if ($request->hasFile('logo')) {
            if ($org->logo) {
                Storage::disk('public')->delete($org->logo);
            }
            $validated['logo'] = $request->file('logo')->store('org-logos', 'public');
        }

        $settings = $org->settings ?? [];
        if (isset($validated['timezone'])) {
            $settings['timezone'] = $validated['timezone'];
        }
        if (isset($validated['currency'])) {
            $settings['currency'] = $validated['currency'];
        }
        unset($validated['timezone'], $validated['currency']);

        $org->update(array_merge($validated, ['settings' => $settings]));

        return back()->with('success', 'Organization settings updated.');
    }
}
