<?php

namespace App\Http\Controllers\Agent;

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

        return view('agent.organization.settings', compact('org'));
    }

    public function update(Request $request)
    {
        $org = $this->getOrg();

        if (auth()->id() !== $org->owner_id) {
            abort(403, 'Only the organization owner can access settings.');
        }

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'primary_color'   => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color'    => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo'            => ['nullable', 'image', 'max:2048'],
            'timezone'        => ['nullable', 'string', 'max:50'],
            'currency'        => ['nullable', 'string', 'max:10'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
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
        if (isset($validated['commission_rate'])) {
            $settings['commission_rate'] = (float) $validated['commission_rate'];
        }
        unset($validated['timezone'], $validated['currency'], $validated['commission_rate']);

        $org->update(array_merge($validated, ['settings' => $settings]));

        return back()->with('success', 'Organization settings updated.');
    }
}
