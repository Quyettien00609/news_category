<?php

namespace App\Http\Controllers\Api\AdminController;


use App\Repositories\CategoryRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    //
    protected $CategoryRepository;


    public function __construct(CategoryRepository $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }
    public function createCategory(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'title' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'parent_id' => 'required',
            'image' => 'nullable|string',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
        ],[
            'title.required'=>'Title không được bỏ trống',
            'description.required'=>'Mô tả không được bỏ trống',
            'content.required'=>'Content không được bỏ trống',
            'parent_id.required'=>'parent_id không được bỏ trống',
            'meta_title.required'=>'meta_title không được bỏ trống',
            'meta_description.required'=>'meta_description không được bỏ trống',
            ]);
        try {
            $slug= Str::slug($request->input('title'));
            $data['slug']=$slug;
            $data['admin_id']=Auth::id();
            $category = $this->CategoryRepository->create($data);
            return response()->json([
                'status' => 201,
                'message' => 'Tạo category thành công',
                'data' => $category
            ]);
        } catch (\Exception $e){
            return response()->json([
                'message'=>'Tạo category thất bại'.$e->getMessage(),
                'status'=>500
            ],500);
        }
    }
    public function addChildCategory(Request $request,$parent_id)
    {
        $data=$request->all();
        $request->validate([
            'title' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
        ],[
            'title.required'=>'Title không được bỏ trống',
            'description.required'=>'Mô tả không được bỏ trống',
            'content.required'=>'Content không được bỏ trống',
            'meta_title.required'=>'meta_title không được bỏ trống',
            'meta_description.required'=>'meta_description không được bỏ trống',
        ]);
        try {
            $category = $this->CategoryRepository->addChildCategory($data,$parent_id);

            return response()->json([
                'status' => 201,
                'message' => 'Tạo category thành công',
                'data' => $category
            ]);
        } catch (\Throwable $e){
            return response()->json([
                'message'=>'Tạo category thất bại'.$e->getMessage(),
                'status'=>500
            ],500);
        }
    }
    public function updateCategory(Request $request,$id)
    {
        $data = $request->all();
        $request->validate([
            'title' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'meta_title' => 'required|string|max:255',
            'meta_description' => 'required|string|max:255',
        ],[
            'title.required'=>'Title không được bỏ trống',
            'description.required'=>'Mô tả không được bỏ trống',
            'content.required'=>'Content không được bỏ trống',
            'meta_title.required'=>'meta_title không được bỏ trống',
            'meta_description.required'=>'meta_description không được bỏ trống',
        ]);
        try {

            $user = $this->CategoryRepository->find($id);

            if ($user) {
                $data = $request->all();
                $user = $this->CategoryRepository->update($id,$data);
                return response()->json([
                    'message' => 'Cập nhật category thành công',
                    'data' => $user,
                    'status'=>201
                ]);
            } else {
                return response()->json([
                    'message' => 'Category không tồn tại',
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


    public function showCategory($id)
    {
        $category = $this->CategoryRepository->showCategory($id);
        if(!$category){
            return response([
                'status' => 500,
                'message' => 'category tìm không tồn tại'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'category dùng tìm được',
            'data' => $category
        ]);
    }
    public function getAllCate(){
        $getAllCate = $this->CategoryRepository->getAllCate();
        return response()->json([
            'message'=>'all',
            'data'=>$getAllCate
        ]);
    }

    public function deleteCategory($id)
    {
        $category = $this->CategoryRepository->deleteCategory($id);

        if ($category) {
            return response()->json([
                'status' => 201,
                'message' => 'Xóa category thành công',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category không tồn tại',
            ]);
        }
    }


    public function searchCate(Request $request){
        try {
        $filterField = $request->input('filters');
        $keyword = $request->input('keyword');

        if (is_string($filterField) && !empty($filterField)) {

            $fields = [$filterField];
        } else {

            $fields = [];
        }
        $allowedValue = ['title', 'slug','description'];
        $orderByValue = $request->input('orderby');
        $orderBy = ['title' => $orderByValue];
        $perPage = $request->input('per_page');
        if ($perPage === null || !is_numeric($perPage) || $perPage < 1) {
            $perPage = 10;
        }

        if ($filterField==null || (in_array($filterField, $allowedValue))) {
            $results = $this->CategoryRepository->getAll($fields, $keyword,$orderBy,$perPage);
            return response()->json([
                'message' => 'Kết quả tìm kiếm',
                'data' => $results,
                'status'=>201
            ]);
        } else {
            return  response()->json([
                'message'=>'Trường tìm kiếm không tồn tại' ,
                'status'=>404
            ]);
        }}catch (\Throwable $e){
            return response()->json([
                'message'=>'Lỗi máy chủ nội bộ: Đã xảy ra lỗi không mong muốn.',
                'status'=>500,
                $e->getMessage()
            ]);
        }
    }
}
