<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\User;
use Validator;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    protected $redirectTo = '/auth/verify';
    protected $redirectAfterLogout = '/';


    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @param  \Illuminate\Contracts\Auth\Registrar $registrar
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => ['getLogout', 'getVerify', 'getConfirm']]);
    }

    public function getVerify() {
        if(\Auth::check()) {
            $data = User::find(\Auth::id());
            if($data->confirm_code == '' || $data->activate == 0) {
                $data->confirm_code = bcrypt($data->username) . rand(0,6000) . 'yourserv';
                $data->save();
            } else {
                return \Redirect::to('/');
            }
            \Mail::send('emails.verify', ['confirm' => $data->confirm_code], function($message) {
                $message->to($this->auth->user()->email)->subject('Verify Account');
            });
        } else {
            return \Redirect::to('/auth/register');
        }
        \Session::flash('message', 'Check your email for a link to confirm your account');
        return \Redirect::to('/');
    }

    public function getConfirm(Request $request) {
        if($request->get('code') == '' || $request->get('code') == NULL) {
            echo 'Sorry we can not found any code';
        } else {
            $user = User::whereConfirmCode($request->get('code'))->get(['id','username', 'activate']);
            foreach($user as $u) {
                $data = User::find($u->id);
                if($data->activate == 1) {
                    return redirect('/auth/login');
                } else {
                    $data->activate = 1;
                    if(\Session::has('message')) {
                        \Session::forget('message');
                    }
                    $data->save();
                }
            }
        }
        return \Redirect::to('/profile')->with('status', 'Thanks - We have confirmed your account');
    }

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
