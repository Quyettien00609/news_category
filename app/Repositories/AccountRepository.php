<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AccountRepository extends BaseRepository implements AuthenticatableContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    public function loginUser($credentials)
    {

        Auth::attempt($credentials);

        return Auth::user();

    }
    public function getAllUser(){
        $user = User::paginate(5);
        return $user;
    }

    public function searchUser(Request $request)
    {
        // Lấy trường tìm kiếm và từ khóa từ request
        $field = $request->input('field');
        $keyword = $request->input('keyword');

        // Xác định trường tìm kiếm dựa trên dữ liệu đầu vào từ người dùng
        $validFields = ['email', 'name']; // Các trường tìm kiếm hợp lệ


            // Trường tìm kiếm hợp lệ, tiến hành tìm kiếm
            $results = $this->model->search($field, $keyword);

            return $results;

    }


    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }

    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}
