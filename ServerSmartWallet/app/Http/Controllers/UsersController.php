<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as User;
use App\Models\Purse as Purse;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller {
    
    //create new user
    public function create($username, $password, $email, $repassword, $purse_id) {
        $data = array('username' => $username, 'password' => $password, 'email' => $email, 'repassword' => $repassword, 'purse_id'=>$purse_id);
        $rules = array(
            'username' => 'required',
            'password' => 'required|min:8',
            'repassword' => 'required:same:password',
            'email' => 'required|email',
            'purse_id' => 'required'
        );
        $validator = \Validator::make($data, $rules); //validation
        if ($validator->fails()) {
            echo $_GET['callback']."(".json_encode("Failed").")";
        } else {
            Purse::create(array('id'=>$purse_id));
            User::create(array(
                'purse_id' => $purse_id,
                'username' => $username,
                'password' => \Hash::make($password),
                'email' => $email
            ));
            echo $_GET['callback']."(".json_encode("Success").")";
        }
    }

    //check login
    public function login($username, $password) {     
        $creds = array('username' => $username, 'password' => $password);
        if (\Auth::attempt($creds)) {
            echo $_GET['callback']."(".json_encode(\Auth::user()->id).")";
        } else {
            echo $_GET['callback']."(".json_encode("Failed").")";
        }
    }

}
