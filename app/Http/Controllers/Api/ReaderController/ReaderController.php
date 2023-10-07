<?php

namespace App\Http\Controllers\Api\ReaderController;

use App\Http\Controllers\Controller;
use App\Repositories\ReaderRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReaderController extends Controller
{
    protected $ReaderRepository;


    public function __construct(ReaderRepository $ReaderRepository)
    {
        $this->ReaderRepository = $ReaderRepository;
    }
    public function createReader(Request $request)
    {
        $data = $request->all();


        try {
            $request->validate([
                'email' => 'required|email|unique:users,email',
            ]);
            $reader = $this->ReaderRepository->create($data);

            return response()->json([
                'status' => 200,
                'message' => 'User created successfully',
                'data' => $reader
            ]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();

            return response()->json([
                'status' => 400,
                'message' => 'Error email',
                'errors' => $errors
            ], 400);
        }
    }

    public function getAllReader(): JsonResponse
    {

        $reader = $this->ReaderRepository->getAllReader();

        return response()->json([
            'message' => 'Tất cả người đọc :',
            'data' => $reader
        ],200);
    }
    public function updateReader(Request $request, $id)
    {
        $data = $request->all();
        $reader = $this->ReaderRepository->find($id);

        if (!$reader) {
            return response()->json('Người dùng không tồn tại');
        }
        try {
            $reader = $this->ReaderRepository->updateReader($id, $data);
        } catch (QueryException) {

            return response()->json([
                'error'=>'email đã tồn tại',
                'status'=>500
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'reader updated successfully',
            'data' => $reader
        ]);
    }
    public function updateReaderByAdmin(Request $request, $id)
    {
        $data = $request->all();
        $reader = $this->ReaderRepository->find($id);

        if (!$reader) {
            return response()->json('Người đọc không tồn tại');
        }
        try {
            $reader = $this->ReaderRepository->updateReaderByAdmin($id, $data);
        } catch (QueryException) {

            return response()->json([
                'error'=>'email đã tồn tại',
                'status'=>500
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'reader updated successfully',
            'data' => $reader
        ]);
    }

    public function getReaderInfo($id)
    {
        $reader = $this->ReaderRepository->getReaderById($id);
        if(!$reader){
            return response([
                'status' => 500,
                'message' => 'Người đọc tìm không tồn tại'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Người đọc tìm được',
            'data' => $reader
        ]);
    }
    public function deleteReader($id)
    {
        $reader = $this->ReaderRepository->deleteReader($id);

        if ($reader) {
            return response()->json([
                'status' => 200,
                'message' => 'Xóa người đọc thành công',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Người đọc không tồn tại',
            ]);
        }
    }
}
