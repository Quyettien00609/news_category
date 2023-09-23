<?php

namespace App\Http\Controllers\Api\AdminController;


use App\Repositories\CategoryRepository\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    //
    protected $CategoryRepository;


    public function __construct(CategoryRepository $CategoryRepository)
    {
        $this->CategoryRepository = $CategoryRepository;
    }
    public function createCategory(Request $request){

        $categories = $this->CategoryRepository->createCategory($request);
        return response()->json([
            'status' => 200,
            'message' => 'User retrieved successfully',
            'data' => $categories,
        ]);
    }
    public function createCategoryparent(Request $request,$parent_id=null){

        $categories = $this->CategoryRepository->createCategoryparent($request,$parent_id);
        return response()->json([
            'status' => 200,
            'message' => 'User retrieved successfully',
            'data' => $categories,
        ]);
    }
    public function getAllCate(){
        $getAllCate = $this->CategoryRepository->getAllCate();
        return response()->json([
            'message'=>'all',
            'data'=>$getAllCate
        ]);
    }
    public function showSubcategories($parent_id){
        $Categories = $this->CategoryRepository->showSubcategories($parent_id);
        return response()->json([
            'message'=>'Các category con',
            'data'=>$Categories
        ]);
    }
    public function findBySlug($slug){
        $Categories = $this->CategoryRepository->findBySlug($slug);
        return response()->json([
            'message'=>'Các category tìm được',
            'data'=>$Categories
        ]);
    }
    public function updateCategory( $id,Request $request)
    {
        $categories = $this->CategoryRepository->updateCategory($id, $request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'data' => $categories
        ]);
    }
    public function deleteCategory($id)
    {
        $this->CategoryRepository->deleteCategory($id);
        return response()->json([
            'status' => 200,
            'message' => 'Category deleted successfully',
            'data' => null
        ]);
    }
}
