<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\OrganizationSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrganizationSettingController extends Controller
{
    public function index(): View
    {
        $setting = OrganizationSetting::current();
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'license_number' => ['nullable', 'string', 'max:100'],
            'report_header' => ['nullable', 'string', 'max:255'],
            'report_footer' => ['nullable', 'string'],
            'doctor_signature' => ['nullable', 'boolean'],
            'default_report_font' => ['required', 'string', 'in:Arial,Helvetica,Times New Roman,Roboto'],
            'default_report_format' => ['required', 'string', 'in:A4,Letter'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'stamp' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = OrganizationSetting::current();
        $validated['doctor_signature'] = $request->has('doctor_signature');

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('organization', 'public');
        }

        if ($request->hasFile('stamp')) {
            if ($setting->stamp && Storage::disk('public')->exists($setting->stamp)) {
                Storage::disk('public')->delete($setting->stamp);
            }
            $validated['stamp'] = $request->file('stamp')->store('organization', 'public');
        }

        $oldValues = $setting->toArray();
        $setting->update($validated);

        AuditLog::log(
            action: 'Updated Organization Settings',
            module: 'Settings',
            recordId: $setting->id,
            recordIdentifier: $setting->name,
            oldValues: $oldValues,
            newValues: $validated
        );

        return back()->with('success', 'Organization and report branding settings updated successfully.');
    }
}
