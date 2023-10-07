<?php
namespace App\Repositories;
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


    public function addChildCategory($attributes = [],$parent_id)
    {
        $parentCategory =Categories::where('parent_id', $parent_id)->first();
        $attributes['parent_id']=$parentCategory->id;
        $attributes['slug'] = Str::slug($attributes['title']);
        $attributes['admin_id']=Auth::id();
        return $this->create($attributes);
    }
    public function getAllCate(){
        {
            return  Categories::with('children')->get();
        }
    }
    public function showCategory($id)
    {
        return Categories::find($id);
    }
    public function findBySlug($slug){
        $category = Categories::where('slug', $slug)->first();

        return $category;
    }
    public function updateCategory($id, $attributes = [])
    {
        return $this->update($id, $attributes);
    }

    public function deleteCategor1y($id)
    {
        $categories = Categories::find($id);

        if ($categories) {
            $categories->delete();
            return true;
        }

        return false;
    }
    public function deleteCategory($id)
    {
        return $this->delete($id);
    }

    public function showChildren($id)
    {
        $category = Categories::with('children')->find($id);

        if (!$category) {
            return response()->json(['message' => 'Parent category not found'], 404);
        }


        return $category;
    }

}
