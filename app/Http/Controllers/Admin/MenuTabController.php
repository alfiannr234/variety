<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuTab;
use Illuminate\Http\Request;

class MenuTabController extends Controller
{
    public function index()
    {
        $tabs = MenuTab::orderBy('order_position', 'asc')->get();
        return view('admin.menu-tabs.index', compact('tabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'url' => 'nullable|string',
            'order_position' => 'required|integer'
        ]);

        MenuTab::create($request->all());
        return back()->with('success', 'Menu Tab berhasil ditambahkan!');
    }

    public function update(Request $request, MenuTab $menuTab)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'url' => 'nullable|string',
            'order_position' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $menuTab->update($data);
        return back()->with('success', 'Menu Tab berhasil diperbarui!');
    }

    public function destroy(MenuTab $menuTab)
    {
        $menuTab->delete();
        return back()->with('success', 'Menu Tab berhasil dihapus!');
    }
}
