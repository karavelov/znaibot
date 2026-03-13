<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BlogDataTable;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogSubCategory;
use App\Models\Gallery;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(BlogDataTable $dataTable)
    {
        return $dataTable->render('admin.blog.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::where('status', 1)->get();
        $galleries = Gallery::where('status', 1)->get();

        return view('admin.blog.create', compact('categories', 'galleries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'title' => ['required', 'max:200', 'unique:blogs,title'],
            'category' => ['required'],
            'gallery_id' => ['nullable'],
            'description' => ['required'],
            'seo_title' => ['nullable', 'max:200'],
            'seo_description' => ['nullable', 'max:200'],
            'youtube_key' => ['nullable', 'url']
        ]);

        $blog = new Blog();

        $base64_image         = $request->base64_image;

        $imagePath = $this->uploadImage($request, 'image', true, $base64_image);


        // Check if an image URL is provided, and store the image from URL if it's there
        if ($request->filled('image_url')) {
            $imagePath = $this->storeImageFromUrl($request->image_url, null);
        }

        // Convert the YouTube link to an embed link if provided
        if ($request->filled('youtube_key')) {
            $blog->youtube_key = $this->convertToEmbedLink($request->youtube_key);
        }

        if($request->filled("date_published")) {
            $blog->created_at = $request->date_published;
        }


        $blog->image = $imagePath;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);

        $blog->category_id = $request->category;
        $blog->sub_category_id = $request->sub_category_id;
        $blog->gallery_id = $request->gallery_id;
        $blog->user_id = Auth::user()->id;
        $blog->description = $request->description;
        $blog->hits=rand(50,100);
        $blog->seo_title = $request->seo_title;
        $blog->seo_description = $request->seo_description;
        $blog->status = $request->status;

        $blog->save();


        // iztrivane na temp ot URL
        $this->deleteTempUploadImage();

        toastr('Успешно създадена публикация', 'success', 'Успешно създаване!');

        return redirect()->route('admin.blog.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::where('status', 1)->get();
        $subcategories=BlogSubCategory::where('blog_category_id', $blog->category_id)->where('status', 1)->get();
        $galleries = Gallery::where('status', 1)->get();
        return view('admin.blog.edit', compact('blog', 'categories', 'galleries','subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => ['nullable', 'image', 'max:3000'],
            'title' => ['required', 'max:200', 'unique:blogs,title,' . $id],
            'category' => ['required'],
            'gallery_id' => ['nullable'],
            'description' => ['required'],
            'seo_title' => ['nullable', 'max:200'],
            'seo_description' => ['nullable', 'max:200']
        ]);

        $blog = Blog::findOrFail($id);

        // Delete temporary image if exists
        $this->deleteTempUploadImage();


        $base64_image         = $request->base64_image;

        $imagePath = $this->updateImage($request, 'image', false, $blog->image, $base64_image);


        // Check if an image URL is provided, and store the image from URL if it's there
        if ($request->filled('image_url')) {
            $imagePath = $this->storeImageFromUrl($request->image_url, $blog->image);
        }


        $blog->image = empty(!$imagePath) ? $imagePath : $blog->image;
        $blog->title = $request->title;
        $blog->slug = Str::slug($request->title);

        $blog->category_id = $request->category;
        $blog->sub_category_id=$request->sub_category_id;
        $blog->gallery_id = $request->gallery_id;
        $blog->user_id = Auth::user()->id;
        $blog->description = $request->description;
        $blog->seo_title = $request->seo_title;
        $blog->seo_description = $request->seo_description;
        $blog->status = $request->status;

        $blog->save();

        toastr('Update successfully', 'success', 'success');

        return redirect()->route('admin.blog.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);
        $this->deleteImage($blog->image);
        $blog->delete();

        return response(['status' => 'success', 'message' => 'Deleted Successfully!']);
    }

    public function changeStatus(Request $request)
    {
        $blog = Blog::findOrFail($request->id);
        $blog->status = $request->status == 'true' ? 1 : 0;
        $blog->save();

        return response(['message' => 'Status has been updated!']);
    }



    public function getImageFromUrl(Request $request)
    {
        $imageUrl = $request->image_url;

        try {
            // Get the image from the URL
            $response = Http::get($imageUrl);

            if ($response->successful()) {
                // Generate a unique file name
                $fileName = 'temp_' . time() . '.jpg';
                // Define the path where the image will be stored
                $filePath = public_path('uploads/' . $fileName);
                // Save the image directly to public/images
                file_put_contents($filePath, $response->body());

                // Return the path of the image stored in public/images
                return response()->json([
                    'status' => 'success',
                    'temp_path' => asset('uploads/' . $fileName)
                ])->header('Access-Control-Allow-Origin', '*');
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The image could not be downloaded.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'There was an error retrieving the image.'
            ]);
        }
    }



    private function convertToEmbedLink($url)
    {

        if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return $url;
    }



    public function getSubcategories(Request $request)
    {
        $subcategories=BlogSubCategory::where('blog_category_id',$request->id)->where('status',1)->get();

        return response()->json($subcategories);
    }
}
