<?php
namespace App\Repositories\NewsRepository;
use App\Models\News;
use App\Models\User;
use App\Models\Categories;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\User_News_Category;

class NewsRepository extends BaseRepository{

    private $NewsRepository;

    public function __construct(News $model)
    {
        parent::__construct($model);
    }
    public function createNews(Request $request){
        $validatedData =  $request->validate([
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'required',
            'thumbnail_image' => 'required',
            'article_image'=>'required',
            'meta_category' => 'required',
            'category'=>'required',
        ]);

        $userId = Auth::id();
        $description = $request->input('description');
        $slug = Str::slug($description);
        $categoryName = $request->input('category');

        // Tìm danh mục dựa trên tên
        $category = Categories::where('title', $categoryName)->first();

        if (!$category) {

            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }

        $news = new News();
        $news->description = $validatedData['description'];
        $news->meta_category = $validatedData['meta_category'];
        $news->meta_title = $validatedData['meta_title'];
        $news->content = $validatedData['content'];
        $news->thumbnail_image =  Str::slug($validatedData['thumbnail_image']);
        $news->article_image = Str::slug($validatedData['article_image']);
        $news->admin_id = $userId;
        $news->slug = $slug;
        $news->category_id = $category->id;
        $news->save();
//
//        $userNewsCategory = new User_News_Category();
//        $userNewsCategory->user_id = $userId;
//        $userNewsCategory->news_id = $news->id;
//        $userNewsCategory->category_id = $category->id;
//        $userNewsCategory->save();

        return $news;
    }
    public function getAllNews(){
        return News::with(['user', 'category'])->get();
    }
    public function updateNews($id, $data)
    {
        $news = News::find($id);

        if (isset($data['description'])) {
            $news->description = $data['description'];
        }
        if (isset($data['content'])) {
            $news->content = $data['content'];
        }
        if (isset($data['meta_title'])) {
            $news->meta_title = $data['meta_title'];
        }
        if (isset($data['meta_category'])) {
            $news->meta_category = $data['meta_category'];
        }
        if (isset($data['thumbnail_image'])) {
            $news->thumbnail_image = Str::slug($data['thumbnail_image']);
        }
        if (isset($data['article_image'])) {
            $news->article_image = Str::slug($data['article_image']);
        }
        if (isset($data['status'])) {
            $news->status = $data['status'];
        }

            $category = Categories::where('title', $data['category'])->first();

            if (!$category) {

                return response()->json(['message' => 'Danh mục không tồn tại'], 404);
            }

        $userId = Auth::id();
        $description =$data['description'];
        $slug = Str::slug($description);
        $news->slug = $slug;
        $news->admin_id = $userId;
        $news->category_id = $category->id;
        $news->save();

        return $news;
    }
    public function deleteNews($id)
    {
        $news = News::find($id);

        if ($news) {
            $news->delete();
            return true;
        }

        return false;
    }

    public function findById($id)
    {
        // Tìm tin tức theo ID và kết nối thông tin user và category
        $news = News::with(['user', 'category'])->find($id);

        if (!$news) {
            // Nếu tin tức không tồn tại, bạn có thể xử lý lỗi ở đây
            return response()->json(['message' => 'Tin tức không tồn tại'], 404);
        }

        return $news;
    }


}
