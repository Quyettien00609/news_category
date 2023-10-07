<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Reader extends Model implements Authenticatable
{
    use HasFactory,HasApiTokens;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'gender',
        'status',
        'address',
        'avatar',
        'role',
        'token'

    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getAuthIdentifierName()
    {
        return 'id'; // Tên cột chứa ID của người dùng trong bảng reader
    }

    public function getAuthIdentifier()
    {
        return $this->getKey(); // Lấy ID của người dùng
    }

    public function getAuthPassword()
    {
        return $this->password; // Tên cột chứa mật khẩu trong bảng reader
    }

    public function getRememberToken()
    {
        return null; // Không sử dụng tính năng "remember me"
    }

    public function setRememberToken($value)
    {
        // Không sử dụng tính năng "remember me"
    }

    public function getRememberTokenName()
    {
        return null; // Không sử dụng tính năng "remember me"
    }
}
