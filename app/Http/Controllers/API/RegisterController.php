<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'email' => 'string|required|email|unique:users',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address' => 'required',
            'date_of_birth' => 'required|date|date_format:Y-m-d|before:today',
            'is_vaccinated' => 'required|in:Yes,No',
            'vaccine_name' => 'required_if:is_vaccinated,Yes'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input = $request->all();
        $input['first_name'] = trim($input['first_name']);
        $input['last_name'] = trim($input['last_name']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $result =  $user;
   
        return $this->sendResponse($success, $result, 'User register successfully.');
    }
    // --------------------------------------------------------------

    public function index()
    {
        $users = User::all();
        return $this->sendResponse($users,'Users retrieved successfully.');
    }
    // --------------------------------------------------------------
}