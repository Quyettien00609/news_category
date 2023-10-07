<?php

namespace App\Http\Controllers\Api\UserController;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\RequestGuard;
use Illuminate\Validation\ValidationException;
use App\Repositories\AccountRepository;
use App\Repositories\ErrorRepository;
class UserController extends Controller
{
    protected $AccountRepository;

    public function __construct(AccountRepository $AccountRepository,ErrorRepository $ErrorRepository)
    {
        $this->AccountRepository = $AccountRepository;
        $this->ErrorRepository = $ErrorRepository;
    }

    protected $ErrorRepository;


    /**
     * Create User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $erorAcc=$this->ErrorRepository->errorexist();
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
                'name' => 'required|string|max:255',
            ], [
                'email.required' => 'Email không được trống',
                'email.email' => 'Email không hợp lệ',
                'password.required' => 'Mật khẩu không được trống',
                'password.min' => 'Mật khẩu ít nhất 6 ký tự',
                'name.required' => 'Tên không được trống',
                'name.string' => 'Tên phải là một chuỗi',
                'name.max' => 'Tên không được dài hơn :max ký tự',
            ]);

            $user = $this->AccountRepository->findBy('email', $request->email);

            if (!$user) {
                $data = $request->all();
                $password = Hash::make( $request->input('password'));
                $user = $this->AccountRepository->create($data);
                $user->password=$password;
                $user->save();

                return response()->json([
                    'message' => 'Đăng ký thành công',
                    'authenticated' => $user,
                    'status'=>201
                ]);
            } else {
                return response()->json([
                    'message' => 'Email đã tồn tại',
                    'authenticated' => $user,
                    'status'=>422
                    ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message'=>'Lỗi máy chủ nội bộ: Đã xảy ra lỗi không mong muốn.',
                'status'=>500
            ]);
        }
    }
    public function loginUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email không được trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được trống',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        ]);
        try {
            $credentials = $request->only('email', 'password');

            $user = $this->AccountRepository->findBy('email', $request->email);
;
            if ($user) {
               $auth =$this->AccountRepository->loginUser($credentials);

                if ($auth) {
                    return response()->json([
                        'message' => 'Đăng nhập thành công',
                        'data' => $user,
                        'token' => $user->createToken("API TOKEN")->plainTextToken
                    ]);
                }
            }
            return response()->json([
                'message'=>'Thông tin tài khoản hoặc mật khẩu không chính xác',
                'status'=>401
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message'=>'Lỗi máy chủ nội bộ: Đã xảy ra lỗi không mong muốn.',
                'status'=>500,
                $e->getMessage()
            ]);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'status' => 200,
                'message' => 'User logged out successfully',
            ]);
    }


}
