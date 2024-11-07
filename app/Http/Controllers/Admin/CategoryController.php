<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest('id')->paginate(5);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('categories','name')
            ],
            'is_active' => [
                'nullable',
                Rule::in([0, 1])
            ]
        ]);

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        // dd($data);

        try {
            Category::create($data);
            // Sau khi thành công, điều hướng về trang danh sách danh mục
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            // Nếu gặp lỗi, điều hướng về lại trang tạo mới và báo lỗi
            return back()->withErrors(['error' => 'An error occurred while creating the category.'])->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => [
                'required',
                Rule::unique('categories')->ignore($category->id)
            ],
            'is_active' => [
                'nullable',
                Rule::in([0, 1])
            ]
        ]);

        try {
            $data['is_active'] = isset($data['is_active']) ? 1 : 0;

            $category->update($data);

            return redirect()
                ->route('admin.categories.edit',$category)
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()
                ->route('admin.categories.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function trash () {
        $trashList = Category::onlyTrashed()->latest('id')->paginate(5);

        return view('admin.categories.trash',compact('trashList'));
    }

    public function forceDestroy($id) {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->forceDelete();
            return redirect()
                ->route('admin.categories.trash')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }

    public function restore($id) {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);

            // dd($category);
            $category->restore();
            return redirect()
                ->route('admin.categories.index')
                ->with('success', true);
        } catch (\Throwable $th) {
            return back()->with('success', false);
        }
    }
}
