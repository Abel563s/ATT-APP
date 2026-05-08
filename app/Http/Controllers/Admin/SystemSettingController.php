<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCode;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $codes = AttendanceCode::all();
        $settings = \App\Models\SystemSetting::all();
        return view('admin.settings.index', compact('codes', 'settings'));
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            \App\Models\SystemSetting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'System settings synchronized.');
    }

    public function updateCode(Request $request, AttendanceCode $code)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
        ]);

        $code->update($request->only(['label', 'bg_color', 'text_color']));

        return redirect()->back()->with('success', 'Core identifier updated.');
    }

    public function storeCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10|unique:attendance_codes,code',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'bg_color' => 'required|string',
            'text_color' => 'required|string',
            'ring_color' => 'nullable|string',
        ]);

        AttendanceCode::create(array_merge($request->all(), [
            'ring_color' => $request->ring_color ?? 'ring-transparent',
            'is_active' => true,
        ]));

        return redirect()->back()->with('success', 'Core identifier created.');
    }

    public function destroyCode(AttendanceCode $code)
    {
        $code->delete();
        return redirect()->back()->with('success', 'Core identifier deleted.');
    }
}
