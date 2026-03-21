<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('categories.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    // ================= DESTROY =================
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}