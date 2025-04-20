<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{

    public function appenRolesAndPermissions($user)
    {
        //roles
        $roles = [];
        foreach ($user->roles as $role) {
            $roles[] = $role->name;
        }
        unset($user['roles']);
        $user['roles'] = $roles;
        //permissions
        $permissions = [];
        foreach ($user->permissions as $permission) {
            $permissions[] = $permission->name;
        }
        unset($user['permissions']);
        $user['permissions'] = $permissions;

        return $user;
    }

    public function register($request): array
    {
        $user = User::query()->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $clientRole = Role::query()->where('name', 'client')->first();
        $user->assignRole($clientRole);
        // Assign permissions associated with the role to the user
        $permissins = $clientRole->permissions()->pluck('name')->toArray();
        $user->givePermissionTo($permissins);

        // load the user,s roles and permissions
        $user->load('roles', 'permissions');// to recognize the roles and permissions

        // reload the user instance to get update roles and permissions
        // I define this command because in the last user before load thy dont recognize to roles and permissions
        $user = User::query()->find($user['id']);
        $user = $this->appenRolesAndPermissions($user);
        $user['token'] = $user->createToken("token")->plainTextToken;

        $message = "user created successfully";
        return ['user' => $user, 'message' => $message];


    }
    public function Login($request): array
    {
        $user = User::where('email', $request->email)->first();

        if (!is_null($user)) {
            if (!Auth::guard('web')->attempt($request->only(['email', 'password']))) {
                $message = "User email & password does not match with our record";
                $code = 401;
            } else {
                $user = $this->appenRolesAndPermissions($user);
                $user['token'] = $user->createToken("token")->plainTextToken;
                $message = "user Logged in successfully";
                $code = 200;
            }
        } else {
            $message = "user not found";
            $code = 404;
        }
        return ['user' => $user, 'message' => $message, 'code' => $code];
    }

    public function logout(): array
    {
        $user = Auth::user();
        if (!is_null($user)) {
            Auth::user()->currentAccessToken()->delete();
            $message = 'User loggout successfully';
            $code = 200;
        } else {
            $message = "invalid token";
            $message = 404;
        }
        return ['user' => $user, 'message' => $message, 'code' => $code];
    }

}

?>