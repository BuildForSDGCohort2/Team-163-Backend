<?php

namespace App\Services;

use App\Traits\ModelTraits;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserServices
{
    use ModelTraits;

    /**
     * Update user details.
     *
     * @param array     $data
     * @param \App\User $user
     */
    public function update($data, $user)
    {
        if (array_key_exists('password', $data)) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);

        return $user;
    }

    /**
     * Update user details.
     *
     * @param array $data
     */
    public function createUser($data)
    {
        $user = new User($data);
        // $user->uuid = $this->generateUniqueData('User', 'uuid');
        $user->password = Hash::make($data['password']);
        $user->save();
    }

    /**
     * Get all users by role.
     *
     * @return object
     */
    public function getAllUsersByRole()
    {
    }

    /**
     * Retrieve user by UUID.
     *
     * @return object
     */
    public function getUserByColumn($column, $value)
    {
        return User::where($column, $value)->firstOrFail();
    }

    /**
     * Change the password of a user.
     */
    public function changePassword(string $password, string $email)
    {
        $user = $this->getUserByColumn('email', $email);
        $user->password = Hash::make($password);
        $user->save();
    }

    /**
     * Get resources using array of values.
     *
     * @param $column
     */
    public function getUsersByArray($column, array $data)
    {
        return User::whereIn($column, $data)->get();
    }
}
