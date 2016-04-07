<?php namespace App\Services;

use App\User;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Validator;

class Registrar implements RegistrarContract
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'fullname' => 'required|max:255',
            'username' => 'required|max:50|unique:users',
            'email' => 'required|email|confirmed|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        $user->userinfo()->create([
            'country' => $data['country'],
            'zip_code' => $data['zip'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'city' => $data['city'],
            'current_place' => $data['current_place'],
            'state' => $data['state'],
        ]);

        $user->roles()->attach(1);

        return $user;
    }

}
