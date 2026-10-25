<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubCategoryController extends Controller
{
    public function create()
    {
        $categories = Category::with('menuTab')->orderBy('name', 'asc')->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect('/admin/categories')->with('success', 'Sub-Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        SubCategory::findOrFail($id)->delete();
        return back()->with('success', 'Sub-Kategori berhasil dihapus!');
    }
}
