<?php

namespace App\Http\Controllers\Api\CommentsController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\CommentsRepository;

class CommentsController extends Controller
{
    //
    protected $CommentsRepository;


    public function __construct(CommentsRepository $CommentsRepository)
    {
        $this->CommentsRepository = $CommentsRepository;
    }
    public function getAllCmt(){
        $getAllCate = $this->CommentsRepository->all();
        return response()->json([
            'message'=>'all',
            'data'=>$getAllCate
        ]);
    }
    public function createCmt(Request $request)
    {

        $data = $request->all();
        $cmt = $this->CommentsRepository->create($data);
        $cmt->save();
        return response()->json([
            'status' => 200,
            'message' => 'Category đã tạo thành công',
            'data' => $cmt
        ]);

    }
}
