<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\BlogSubCategoryDataTable;
use App\Models\BlogCategory;
use App\Models\BlogSubCategory;
use Illuminate\Support\Str;

class BlogSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogSubCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.blog.blog-subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blogCategories=BlogCategory::where('status',1)->get();

        return view('admin.blog.blog-subcategory.create',compact('blogCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id' => ['required'],
            'name' => ['required', 'max:200', 'unique:blog_sub_categories,name'],
            'status' => ['required']
        ]);

        $blogSubCategory= new BlogSubCategory();

        $blogSubCategory->blog_category_id=$request->blog_category_id;
        $blogSubCategory->name=$request->name;
        $blogSubCategory->slug=Str::slug($request->name);
        $blogSubCategory->status=$request->status;

        $blogSubCategory->save();

        toastr('Успешно добавяне на подкатегория!', 'success', 'Успешно добавяне');

        return redirect()->route('admin.blog-sub-category.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blogSubCategory=BlogSubCategory::findOrFail($id);
        $blogCategories=BlogCategory::where('status',1)->get();

        return view('admin.blog.blog-subcategory.edit', compact('blogSubCategory','blogCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'blog_category_id' => ['required'],
            'name' => ['required', 'max:200', 'unique:blog_sub_categories,name,'.$id],
            'status' => ['required']
        ]);

        $blogSubCategory= BlogSubCategory::findOrFail($id);

        $blogSubCategory->blog_category_id=$request->blog_category_id;
        $blogSubCategory->name=$request->name;
        $blogSubCategory->slug=Str::slug($request->name);
        $blogSubCategory->status=$request->status;

        $blogSubCategory->save();

        toastr('Успешно редактиране на подкатегория!', 'success', 'Успешно редактиране');

        return redirect()->route('admin.blog-sub-category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blogSubCategory=blogSubCategory::findOrFail($id);


        $blogSubCategory->delete();


        return response(['status' => 'success', 'message' => 'Успешно изтриване!']);
    }

    public function changeStatus(Request $request)
    {
        $category = BlogSubCategory::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();

        return response(['message' => 'Статусът е актуализиран успешно!']);
    }
}
