<?php
namespace App\Repositories;
class ErrorRepository{
    public function errorAcc(){
        return response()->json([
            'authenticated' => 'Thông tin tài khoản hoặc mật khẩu không chính xác',
        ]);
    }
    public function errorexist()
{
    return response()->json([
        'Lỗi' => 'Người dùng đã tồn tại',
    ]);
}
    public function errorNotExist()
    {
        return response()->json([
            'Lỗi' => 'Người dùng không tồn tại',
        ]);
    }



}
