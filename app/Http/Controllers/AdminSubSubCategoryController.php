<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSubSubCategoryController extends Controller
{
    public function create()
    {
        // Ambil semua sub-kategori beserta induknya agar dropdown-nya jelas
        $subCategories = SubCategory::with('category')->get();
        return view('admin.categories.create_sub_sub', compact('subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:sub_categories,id',
            'name' => 'required|string|max:255',
        ], [
            'sub_category_id.required' => 'Pilih Induk Sub-Kategori (Level 2) terlebih dahulu.',
            'name.required' => 'Nama spesifikasi / Sub-Sub Kategori wajib diisi.',
        ]);

        SubSubCategory::create([
            'sub_category_id' => $request->sub_category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect('/admin/categories')->with('success', 'Sub-Sub Kategori (Level 3) berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $subSub = SubSubCategory::findOrFail($id);
        $subSub->delete();

        return redirect('/admin/categories')->with('success', 'Sub-Sub Kategori berhasil dihapus!');
    }
}
