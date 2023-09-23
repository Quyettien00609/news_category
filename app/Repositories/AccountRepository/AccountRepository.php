<?php

namespace App\Repositories\AccountRepository;

use App\Models\User;
use App\Repositories\BaseRepository;
class AccountRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::find($id);
    }

    public function createAccount($data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();

        return $user;
    }

    public function updateAccount($id, $data)
    {
        $user = User::find($id);

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['role'])) {
            $user->role = $data['role'];
        }
        $user->save();

        return $user;
    }

    public function deleteAcconut($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return true;
        }

        return false;
    }







}
