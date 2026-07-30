<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoreConfig;
use Illuminate\Http\Request;
use Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $newReleaseDays = CoreConfig::value('new_release_days_limit') ?? 14;
        return view('admin.settings.index', compact('newReleaseDays'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'new_release_days_limit' => 'required|integer|in:10,15,25,30',
        ]);

        $config = CoreConfig::firstOrNew(['path' => 'new_release_days_limit']);
        $config->value = $request->new_release_days_limit;
        $config->user_id = auth()->id();
        $config->updated_by = auth()->id();
        if (!$config->exists) {
            $config->created_by = auth()->id();
        }
        $config->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully');
    }
}