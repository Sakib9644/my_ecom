<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $siteName = Setting::get('site_name', 'TechEx Store');
        return view('admin.settings.index', compact('siteName'));
    }

    public function updateSiteName(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        Setting::set('site_name', $request->site_name);

        return redirect()->route('admin.settings.index')->with('success', 'Site name updated successfully.');
    }

    public function updateFavicon(Request $request)
    {
        $request->validate([
            'favicon' => 'required|image|mimes:ico,png,svg,jpg,jpeg|max:1024',
        ]);

        // Delete old favicon if exists
        $oldFavicon = Setting::get('favicon');
        if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
            Storage::disk('public')->delete($oldFavicon);
        }

        // Store new favicon in the public disk under a 'settings' directory
        $path = $request->file('favicon')->store('settings', 'public');
        Setting::set('favicon', $path);

        return redirect()->route('admin.settings.index')->with('success', 'Favicon updated successfully.');
    }

    public function removeFavicon()
    {
        $favicon = Setting::get('favicon');
        if ($favicon && Storage::disk('public')->exists($favicon)) {
            Storage::disk('public')->delete($favicon);
        }
        Setting::set('favicon', null);

        return redirect()->route('admin.settings.index')->with('success', 'Favicon removed successfully.');
    }
}
