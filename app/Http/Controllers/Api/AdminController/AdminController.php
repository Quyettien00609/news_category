<?php

namespace App\Http\Controllers\Api\AdminController;

use App\Repositories\AccountRepository\AccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
class AdminController extends Controller
{
    protected $AccountRepository;

    public function __construct(AccountRepository $AccountRepository)
    {
        $this->AccountRepository = $AccountRepository;
    }

    public function getAllUser(): JsonResponse
    {

        $users = $this->AccountRepository->getAllUsers();

        return response()->json([
            'message' => 'Users retrieved successfully',
            'data' => $users
        ],200);
    }

    public function getUserInfo($id)
    {
        $user = $this->AccountRepository->getUserById($id);

        return response()->json([
            'status' => 200,
            'message' => 'User retrieved successfully',
            'data' => $user
        ]);
    }

    public function createUser(Request $request)
    {
        $user = $this->AccountRepository->createAccount($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'User created successfully',
            'data' => $user
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = $this->AccountRepository->updateAccount($id, $request->all());

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function deleteUser($id)
    {
        $this->AccountRepository->deleteAcconut($id);
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully',
            'data' => null
        ]);
    }
}
