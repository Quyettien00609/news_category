<?php

namespace App\Http\Controllers\Api\AdminController;


use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    //
    protected $NewsRepository;


    public function __construct(NewsRepository $NewsRepository)
    {
        $this->NewsRepository = $NewsRepository;
    }
    public function createNews1(Request $request){

        $createNews = $this->NewsRepository->createNews($request->all());
        return response()->json([
            'status' => 200,
            'message' => 'News đã tạo thành coong',
            'data' => $createNews,
        ]);
    }
    public function createNews(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'title' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required',
            'thumbnail_image' => 'nullable|string',
            'article_imgage' => 'nullable|string',
            'meta_title' => 'required|string|max:255',
            'meta_category' => 'required|string|max:255',
        ],[
            'title.required'=>'Title không được bỏ trống',
            'description.required'=>'Mô tả không được bỏ trống',
            'content.required'=>'Content không được bỏ trống',
            'category_id.required'=>'Id của không được bỏ trống',
            'thumbnail_image.required'=>'thumbnail không được bỏ trống',
            'article_imgage.required'=>'article không được bỏ trống',
            'meta_title.required'=>'meta_title không được bỏ trống',
            'meta_category.required'=>'meta_category không được bỏ trống',
        ]);
        try {
            $category =$this->NewsRepository->find( $request->input('category_id'));

            if (!$category){
                return response()->json([
                    'status' => 404,
                    'message' => 'Không tìm thấy category liên kết',
                ]);
                }
            else{
                $slug= Str::slug($request->input('title'));
                $data['slug']=$slug;
                $data['admin_id']=Auth::id();
                $news = $this->NewsRepository->create($data);
                return response()->json([
                    'status' => 201,
                    'message' => 'Tạo tin tức thành công',
                    'data' => $news
                ]);
            }
        } catch (\Exception $e){
            return response()->json([
                'message'=>'Tạo tin tức thất bại'.$e->getMessage(),
                'status'=>500
            ],500);
        }
    }
    public function getAllNews(){
        $getAllNews = $this->NewsRepository->getAllNews();
        return response()->json([
            'message'=>'all',
            'data'=>$getAllNews
        ]);
    }
    public function updateNews(Request $request,$id)
    {
        $data = $request->all();
        $request->validate([
            'title' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required',
            'thumbnail_image' => 'required|string',
            'article_image' => 'required|string',
            'meta_title' => 'required|string|max:255',
            'meta_category' => 'required|string|max:255',
            'status'=>'required'
        ],[
            'title.required'=>'Title không được bỏ trống',
            'description.required'=>'Mô tả không được bỏ trống',
            'content.required'=>'Content không được bỏ trống',
            'category_id.required'=>'Id của không được bỏ trống',
            'thumbnail_image.required'=>'thumbnail không được bỏ trống',
            'article_image.required'=>'article không được bỏ trống',
            'meta_title.required'=>'meta_title không được bỏ trống',
            'meta_category.required'=>'meta_category không được bỏ trống',
            'status.required'=>'Trạng thái không được bỏ trống',
        ]);
        try {

            $user = $this->NewsRepository->find($id);

            if ($user) {
                $data = $request->all();
                $user = $this->NewsRepository->update($id,$data);
                return response()->json([
                    'message' => 'Cập nhật tin tức thành công',
                    'status'=>201,
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'message' => 'Tin tức không tồn tại',
                    'status'=>404,
                ]);
            }
        } catch (\Throwable $e){
            return response()->json([
                'message'=>'Cập nhật thất bại'.$e->getMessage(),
                'status'=>500
            ],500);
        }
    }
    public function updateSlugNews(Request $request,$id)
    {
        $data = $request->all();
        $request->validate([
            'slug' => 'required|string|max:255',
        ],[
            'slug.required'=>'Title không được bỏ trống',
        ]);
        try {

            $user = $this->NewsRepository->find($id);

            if ($user) {
                $data = $request->all();
                $user = $this->NewsRepository->update($id,$data);
                return response()->json([
                    'message' => 'Cập nhật slug thành công',
                    'status'=>201,
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'message' => 'Tin tức không tồn tại',
                    'status'=>404,
                ]);
            }
        } catch (\Throwable $e){
            return response()->json([
                'message'=>'Cập nhật thất bại'.$e->getMessage(),
                'status'=>500
            ],500);
        }
    }

    public function deleteNews($id)
    {
        $this->NewsRepository->deleteNews($id);
        return response()->json([
            'status' => 200,
            'message' => 'Category deleted successfully',
            'data' => null
        ]);
    }
    public function findNewsId($id){
        $News = $this->NewsRepository->findNewsId($id);
        return response()->json([
            'message'=>'Các category tìm được',
            'data'=>$News
        ]);
    }
}
