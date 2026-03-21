<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\Plugin;

class PluginController extends Controller
{
    public function index()
    {
        $plugins = Plugin::orderBy('plugin_name')->get();
        return view('admin.plugins.index', compact('plugins'));
    }

    public function toggle(int $id)
    {
        $plugin = Plugin::findOrFail($id);
        $plugin->update(['plugin_status' => !$plugin->plugin_status]);
        $state = $plugin->plugin_status ? 'enabled' : 'disabled';
        return back()->with('success', "Plugin [{$plugin->plugin_name}] {$state}.");
    }
}
