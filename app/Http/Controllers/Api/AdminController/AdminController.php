<?php

namespace App\Http\Controllers\Api\AdminController;
use Illuminate\Database\QueryException;
use App\Repositories\AccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Repositories\ErrorRepository;
class AdminController extends Controller
{
    protected $AccountRepository;

    public function __construct(AccountRepository $AccountRepository,ErrorRepository $ErrorRepository)
    {
        $this->AccountRepository = $AccountRepository;
        $this->ErrorRepository = $ErrorRepository;
    }

    public function getAllUser()
    {

        $users = $this->AccountRepository->getAllUser();

        return response()->json([
            'message' => 'Danh sách người dùng',
            'data' => $users
        ],201);
    }

    public function getUserInfo($id)
    {
        try {
            $user = $this->AccountRepository->find($id);
            if($user){
                return response()->json([
                    'message'=>'Người dùng tìm được',
                    'status'=>200,
                    'data'=>$user
                ]);
            }
            return response()->json([
                'message'=>'Người dùng không tồn tại',
                'status'=>404,
            ]);
        }catch (\Throwable $e){
            return response()->json([
                'message'=>'Lỗi không xác định'.$e->getMessage(),
                'status'=>500,
            ]);

        }

    }
    public function createUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'name.required' => 'Tên không được trống',
            'email.required' => 'Email không được trống',
            'email.unique'=>'Email đã được đăng ký',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được trống',
            'password.min:6' => 'Mật khẩu ít nhất 6 ký tự',
        ]);
        try {
                $data = $request->all();
                $password = Hash::make( $request->input('password'));
                $user = $this->AccountRepository->create($data);
                $user->password=$password;
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'User created successfully',
                    'data' => $user
                ]);

        }  catch (\Exception $e){
            return response()->json([
                'message'=>'Tạo người dùng thất bại',
                'status'=>500
            ]);
        }
    }

    public function updateUser( $id,Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,',
            'name' => 'required|string',
            'role' => 'required',
        ], [
            'name.required' => 'Tên không được trống',
            'email.required' => 'Email không được trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'role.required'=>'Role không được trống',
        ]);
        try {
            $user = $this->AccountRepository->find($id);

            if ($user) {
                $data = $request->all();
                $user = $this->AccountRepository->update($id,$data);
                return response()->json([
                    'message' => 'update người dùng thành công',
                    'data' => $user,
                    'status'=>201
                ]);
            } else {
                return response()->json([
                    'message' => 'Người dùng không tồn tại',
                    'status'=>404,
                ]);
            }
        }   catch (\Throwable $e) {
            return response()->json([
                'message'=>'Lỗi máy chủ nội bộ: Đã xảy ra lỗi không mong muốn.',
                'status'=>500,
                $e->getMessage()
            ]);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = $this->AccountRepository->find($id);
            if($user){
                $this->AccountRepository->delete($id);
                return response()->json([
                    'message'=>'Xóa người dùng thành công',
                    'status'=>200
                ]);
            }
            return response()->json([
                'message'=>'Không tìm thấy người dùng',
                'status'=>404
            ]);
        }catch (\Throwable $e){
            return response()->json([
                'message'=>'Lỗi máy chủ nội bộ: Đã xảy ra lỗi không mong muốn.',
                'status'=>500,
                $e->getMessage()
            ]);
        }
    }

    public function getAll(Request $request){
        try {
        $filterField = $request->input('filters');
        $keyword = $request->input('keyword');
        if (is_string($filterField) && !empty($filterField)) {

            $fields = [$filterField];
        } else {

            $fields = [];
        }
        $allowedValue = ['name', 'email'];
        $orderByValue = $request->input('orderby');
        $orderBy = ['name' => $orderByValue];
        $perPage = $request->input('per_page');
        if ($perPage === null || !is_numeric($perPage) || $perPage < 1) {
            $perPage = 10;
        }

        if ($filterField==null || (in_array($filterField, $allowedValue))) {
            $results = $this->AccountRepository->getAll($fields, $keyword,$orderBy,$perPage);
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
