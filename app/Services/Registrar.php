<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255|regex:/^[A-Za-z ]*$/',
			'email' => 'required|email|max:255|unique:mct_user,v_email',
			'phonenumber' => 'required|min:10|numeric',
			'password' => 'required|confirmed|min:6',
			'password' => 'required|min:6',
		]);
	}
	
	public function validate_sms_passkey(array $data)
	{
		return Validator::make($data, [
			'passkey' => 'required|min:7|numeric',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'v_email' => $data['email'],
			'v_password' => bcrypt($data['password']),
		]);
	}

}
