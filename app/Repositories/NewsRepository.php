<?php
namespace App\Repositories;
use App\Models\News;
use App\Models\User;
use App\Models\Categories;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Model\User_News_Category;

class NewsRepository extends BaseRepository{

    private $NewsRepository;

    public function __construct(News $model)
    {
        parent::__construct($model);
    }
    public function createNews($attributes = [])
    {
        $attributes['slug'] = Str::slug($attributes['title']);
        $attributes['admin_id']=Auth::id();
        return $this->create($attributes);
    }

    public function getAllNews(){
        return News::all();
    }
    public function updateNews($id, $attributes = [])
    {
        return $this->update($id, $attributes);
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
    public function updateSlugNews($id, $attributes = [])
    {
        return $this->update($id, $attributes);
    }


}
