<?php
namespace App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\User;
use App\Models\Categories;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CategoryRepository extends BaseRepository{

    public function __construct(Categories $model)
    {
        parent::__construct($model);
    }

    public function createCategory(Request $request){

        $validatedData =  $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'meta_title' => 'required',
                'content'=>'required',
                'meta_description' => 'required',
                'image' => 'required',
                'parent_id' => 'required',
            ]);

        $userId = Auth::id();
        $title = $request->input('title');
        $slug = Str::slug($title); // Chuyển đổi tiêu đề thành slug

        // Tạo tin tức

        $category = new Categories();
        $category->title = $validatedData['title'];
        $category->description = $validatedData['description'];
        $category->meta_title = $validatedData['meta_title'];
        $category->content = $validatedData['content'];
        $category->meta_description = $validatedData['meta_description'];
        $category->image = $validatedData['image'];
        $category->admin_id = $userId;
        $category->slug = $slug;
        $category->parent_id = $validatedData['parent_id'];
        $category->save();


        // Check tag có chưa để tạo
        return $category;
    }
    public function createCategoryparent(Request $request,$parent_id = null){
        $parentCategory = Categories::findOrFail($parent_id);


        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'meta_title' => 'required',
            'content'=>'required',
            'meta_description' => 'required',
            'image' => 'required',
        ]);

        $userId = Auth::id();
        $title = $request->input('title');
        $slug = Str::slug($title); // Chuyển đổi tiêu đề thành slug

        // Tạo tin tức

        $category = new Categories();
        $category->title = $validatedData['title'];
        $category->description = $validatedData['description'];
        $category->meta_title = $validatedData['meta_title'];
        $category->content = $validatedData['content'];
        $category->meta_description = $validatedData['meta_description'];
        $category->image = Str::slug($validatedData['image']);
        $category->admin_id = $userId;
        $category->slug = $slug;
        $category->parent_id =  $parentCategory->id;
        $category->save();


        // Check tag có chưa để tạo
        return $category;
    }
    public function getAllCate(){
        {
            return Categories::with('User')->get();

        }
    }
    public function showSubcategories($parent_id)
    {
        $parentCategory = Categories::find($parent_id);

        if (!$parentCategory) {
            return response()->json(['message' => 'Parent category not found'], 404);
        }

        $categories = $parentCategory->children;
        return $categories;
    }
    public function findBySlug($slug)
    {
        // Tìm danh mục dựa trên slug
        $category = Categories::where('slug', $slug)->first();

        if (!$category) {
            // Trả về lỗi nếu danh mục không tồn tại
            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }

        // Trả về thông tin của danh mục
        return response()->json($category);

}
    public function updateCategory($id, $data)
    {
        $categories = Categories::find($id);

        if (isset($data['title'])) {
            $categories->title = $data['title'];
        }
        if (isset($data['description'])) {
            $categories->description = $data['description'];
        }
        if (isset($data['meta_title'])) {
            $categories->meta_title = $data['meta_title'];
        }
        if (isset($data['meta_description'])) {
            $categories->meta_description = $data['meta_description'];
        }
        if (isset($data['image'])) {
            $categories->image = Str::slug($data['image']);
        }
        if (isset($data['status'])) {
            $categories->status = $data['status'];
        }
        if (isset($data['content'])) {
            $categories->content = $data['content'];
        }
        $userId = Auth::id();
        $title =$data['title'];
        $slug = Str::slug($title);
        $categories->slug = $slug;
        $categories->admin_id = $userId;
        $categories->save();

        return $categories;
    }
    public function deleteCategory($id)
{
    $categories = Categories::find($id);

    if ($categories) {
        $categories->delete();
        return true;
    }

    return false;
}
    public function findNewsId($id)
    {
        // Tìm tin tức theo ID và kết nối thông tin user và category
        $news = Categories::with(['user', 'news'])->find($id);

        if (!$news) {
            // Nếu tin tức không tồn tại, bạn có thể xử lý lỗi ở đây
            return response()->json(['message' => 'Tin tức không tồn tại'], 404);
        }

        return $news;
    }

}
