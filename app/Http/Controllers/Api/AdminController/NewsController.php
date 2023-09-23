<?php

namespace App\Http\Controllers\Api\AdminController;


use App\Repositories\NewsRepository\NewsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    //
    protected $NewsRepository;


    public function __construct(NewsRepository $NewsRepository)
    {
        $this->NewsRepository = $NewsRepository;
    }
    public function createNews(Request $request){

        $createNews = $this->NewsRepository->createNews($request);
        return response()->json([
            'status' => 200,
            'message' => 'User retrieved successfully',
            'data' => $createNews,
        ]);
    }
    public function getAllNews(){
        $getAllNews = $this->NewsRepository->getAllNews();
        return response()->json([
            'message'=>'all',
            'data'=>$getAllNews
        ]);
    }
    public function updateNews( $id,Request $request)
    {
        $news = $this->NewsRepository->updateNews($id, $request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'data' => $news
        ]);
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
