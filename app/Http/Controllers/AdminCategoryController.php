<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuTab;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['subCategories.subSubCategories', 'subCategories.products', 'menuTab'])->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }
    public function create()
    {
        $menuTabs = MenuTab::where('is_active', true)->orderBy('order_position', 'asc')->get();
        return view('admin.categories.create', compact('menuTabs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'menu_tab_id' => 'required|exists:menu_tabs,id'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'menu_tab_id' => $request->menu_tab_id,
        ]);

        return redirect('/admin/categories')->with('success', 'Kategori berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        // Ambil data tab untuk ditampilkan di form edit
        $menuTabs = MenuTab::where('is_active', true)->orderBy('order_position', 'asc')->get();

        return view('admin.categories.edit', compact('category', 'menuTabs'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'menu_tab_id' => 'required|exists:menu_tabs,id'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'menu_tab_id' => $request->menu_tab_id,
        ]);

        return redirect('/admin/categories')->with('success', 'Kategori berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $hasProducts = \App\Models\Product::whereHas('subSubCategory.subCategory', function ($q) use ($category) {
            $q->where('category_id', $category->id);
        })->exists();

        if ($hasProducts) {
            return back()->with('error', 'Gagal! Kategori ini masih berisi produk.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}
