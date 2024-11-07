<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Users\Users;
use App\Libraries\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get User List
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUsers(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 10);

        $totalUsers = Users::from('dlab.users as users')->count();
        $totalPages = ceil($totalUsers / $perPage);
        if ($page > $totalPages) {
            return JsonResponse::Failed('Page number exceeds total pages available');
        }

        $users = $this->queryGetUser()->paginate($perPage, ['*'], 'page', $page);
        if ($users->isEmpty()) {
            return JsonResponse::NotFound('Users empty');
        }

        return JsonResponse::Success($users, 'Request Success');
    }

    /**
     * Get User By Id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getUserById(int $id)
    {
        $users = $this->queryGetUser()
            ->where('users.id', $id)
            ->get();

        if ($users->isEmpty()) {
            return JsonResponse::NotFound('User not found');
        }

        return JsonResponse::Success($users, 'Request Success');
    }

    /**
     * Create User
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createUser(Request $request)
    {
        if ($request->user['roles_name'] == 'member') {
            return JsonResponse::BadDynamic('Unauthorized access', 401);
        }

        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|unique:users,email',
            'nama'     => 'required|string',
            'umur'     => 'required|integer|min:1',
            'password' => 'required|string|min:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->user['roles_name'] == 'admin') {
                $statusValidation = Validator::make($request->all(), [
                    'status' => 'required|in:admin,moderator,member'
                ]);
            } else {
                $statusValidation = Validator::make($request->all(), [
                    'status' => 'required|in:member'
                ]);
            }

            if ($statusValidation->fails()) {
                $validator->errors()->merge($statusValidation->errors());
            }
        });

        if ($validator->fails()) {
            return JsonResponse::Failed($validator->errors()->first());
        }

        try {
            $users = new Users();
            $users->email = $request->email;
            $users->nama = $request->nama;
            $users->umur = $request->umur;
            $users->password = Hash::make($request->password);
            $users->role = $request->status == 'admin' ? 1 : ($request->status == 'moderator' ? 2 : 3);
            $users->save();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return JsonResponse::Failed('Registration Failed');
        }

        return JsonResponse::Success([], 'Registration successful');
    }

    /**
     * Update User By Id
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateUser(Request $request, int $id)
    {
        $users = Users::where('id', $id)->first();
        if (empty($users)) {
            return JsonResponse::NotFound('User not found');
        }

        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|unique:users,email,' . $id,
            'nama'     => 'required|string',
            'umur'     => 'required|integer|min:1',
            'password' => 'required|string|min:6',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->user['roles_name'] == 'admin') {
                $statusValidation = Validator::make($request->all(), [
                    'status' => 'required|in:admin,moderator,member'
                ]);
            } else {
                $statusValidation = Validator::make($request->all(), [
                    'status' => 'required|in:member'
                ]);
            }

            if ($statusValidation->fails()) {
                $validator->errors()->merge($statusValidation->errors());
            }
        });

        if ($validator->fails()) {
            return JsonResponse::Failed($validator->errors()->first());
        }

        try {
            $users->email = $request->email;
            $users->nama = $request->nama;
            $users->umur = $request->umur;
            $users->password = Hash::make($request->password);
            $users->role = $request->status == 'admin' ? 1 : ($request->status == 'moderator' ? 2 : 3);
            $users->save();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return JsonResponse::Failed('Failed update data');
        }

        return JsonResponse::Success([], 'Data updated successfully');
    }

    /**
     * Delete User By Id
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteUser(Request $request, int $id)
    {
        if ($request->user['roles_name'] == 'member') {
            return JsonResponse::BadDynamic('Unauthorized access', 401);
        }

        $users = Users::where('id', $id);
        if ($users->get()->isEmpty()) {
            return JsonResponse::NotFound('User not found');
        }

        try {
            $users->delete();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return JsonResponse::Failed('Failed delete data');
        }

        return JsonResponse::Success($users, 'Data deleted successfully');
    }

    /**
     * Query Get User
     *
     * @return Users
     */
    private function queryGetUser()
    {
        return Users::from('dlab.users as users')
            ->leftJoin('dlab.user_role as roles', function($join) {
                $join->on('roles.id', '=', 'users.role');
            })
            ->select(
                'users.id', 
                'users.email', 
                'users.nama', 
                'users.umur', 
                'roles.roles as roles_name', 
                'users.password'
            );
    }
}