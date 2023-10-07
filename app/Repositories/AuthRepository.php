<?php
namespace App\Repositories;

use App\Models\Reader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AuthRepository extends BaseRepository implements AuthenticatableContract
{
    use Authenticatable;
    public function __construct(Reader $model)
    {
        parent::__construct($model);
    }
    public function login($credentials)
    {

       $user =  Auth::guard('reader')->attempt($credentials);

        return $user;


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
