<?php
namespace App\Http\Controllers\Api\ReaderController;

use App\Http\Controllers\Controller;
use App\Models\Reader;
use App\Repositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AuthRepository;
use App\Repositories\ReaderRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ReaderAccountController extends Controller
{
    protected $AuthRepository;

    public function __construct(AuthRepository $AuthRepository)
    {
        $this->AuthRepository = $AuthRepository;
    }






    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ], [
                'email.required' => 'Email không được trống',
                'email.email' => 'Email không hợp lệ',
                'password.required' => 'Mật khẩu không được trống',
                'password.min' => 'Mật khẩu ít nhất 6 ký tự',
            ]);

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            $read = $this->AuthRepository->findBy('email', $request->email);

            if ($read) {
                $reader = $this->AuthRepository->login($credentials);
                $token = $read-> createToken("API TOKEN")->plainTextToken;
                $read->token = Hash::make($token);
                $read->save();
                return response()->json([
                    'message' => 'Đăng nhập thành công',
                    'authenticated' => $reader,
                    'token'=>$token


                ]);
            } else {
                return response()->json([
                    'authenticated' => 'Thông tin tài khoản hoặc mật khẩu không chính xác',
                ]);
            }
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $errorMessages = [];
            foreach ($errors->all() as $error) {
                $errorMessages[] = $error;
            }

            return response()->json([
                'errors' => $errorMessages,
            ], 422);
        }
    }
    public function logout()
    {
        $this->AuthRepository->logout();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }
    public function check()
    {
        $authenticated = $this->AuthRepository->check();
        $user = $this->AuthRepository->user();

        if ($authenticated) {
            return response()->json([
                'authenticated' => true,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'authenticated' => false,
            ]);
        }
    }

}
