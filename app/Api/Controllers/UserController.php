<?php
namespace App\Api\Controllers;

use App\Api\Requests\ChangePasswordRequest;

use App\Api\Requests\RegisterRequest;
use App\Api\Requests\LoginRequest;
use App\Api\Requests\ForgetPasswordRequest;
use App\Api\Requests\NearByUserRequest;

use App\Api\Requests\SetPasswordRequest;
use App\Api\Requests\SocialRegisterRequest;
use App\Api\Requests\UpdateRegisterRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgetPasswordNotification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @resource Auth
 */
class UserController extends Controller
{
    /**
     * Login
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "token" : "$token"
     * }
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status_code'   => 400,
                    'message'       => 'Incorrect email address or password'], 400);
            }
            $user = \Auth::user();
            $user->role = $user->roles()->first()->id;
            return response()->json([
                'status_code' => '200',
                'data'        => $user,
                'token'       => $token,
            ]);
        } catch (JWTException $e) {
            return response()->json(['status_code' => 500, 'message' => 'Could not create token'], 500);
        }
    }
    /**
     * Register
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "token" : "$token"
     * }
     */
    public function register(RegisterRequest $request)
    {
        $insert_data = $request->only(['name', 'email', 'password', 'phone', 'dob', 'country', 'profile_pic', 'address', 'latitude', 'longitude', 'gender' ]);

        if ($insert_data['phone']) {
            $insert_data['phone'] = substr($insert_data['phone'], -10);
        }
        if ($insert_data['dob']) {
            $insert_data['dob'] = date('Y-m-d', strtotime($insert_data['dob']));
        }

        $insert_data['password'] = Hash::make($insert_data['password']);
        $user                    = User::create($insert_data);
        $user->roles()->sync($request->role_id);
        $token = JWTAuth::fromUser($user);

        return response()->json(['status_code' => 200, 'data' => $user, 'token' => $token], 200);
    }
    /**
     * Forget Password
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('email', $request->get('email'))->first();
        if (!$user) {
            throw new \Exception("Entered email address not found.", 400);
        } else {
            $user->update(['remember_token' => str_random(10)]);
            $user['password'] = str_random(6);
            $hash_password    = Hash::make($user['password']);
            $user->notify(new ForgetPasswordNotification($user));
            $password_update = User::where('id', $user->id)->update(['password' => $hash_password]);
            return response()->json([
                'status_code' => '200',
                'message'     => 'Please check your email address to reset password.',
            ]);
        }
    }
    /**
     * Social Media Register
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "token" : "$token"
     * }
     */
    public function socialMediaRegister(SocialRegisterRequest $request)
    {
        $user      = User::where('social_media_id', $request->get('social_media_id'))->where('social_media_type', $request->get('social_media_type'))
            ->first();
        $not_found = true;
        if ($user) {
            $not_found         = false;
            $user->timezone    = $request->get('timezone', 'UTC');
            $user->save();
        }
        $user = User::where('email', $request->get('email'))->first();
        if ($user && $not_found) {
            $not_found                  = false;
            $user->social_media_type    = $request->get('social_media_type');
            $user->social_media_id      = $request->get('social_media_id');
            $user->timezone             = $request->get('timezone', 'UTC');
            $user->save();
        }

        $insert_data = $request->only(['name', 'email', 'password', 'phone', 'dob', 'country', 'profile_pic', 'address', 'latitude', 'longitude', 'gender' ]);

        if ($insert_data['phone']) {
            $insert_data['phone'] = substr($insert_data['phone'], -10);
        }
        if ($insert_data['dob']) {
            $insert_data['dob'] = date('Y-m-d', strtotime($insert_data['dob']));
        }

        $token = JWTAuth::fromUser($user);
        if ($not_found) {
            $insert_data['password'] = Hash::make($insert_data['password']);
            $user                    = User::create($insert_data);
            $user->roles()->sync($request->role_id);
        }
        $token = JWTAuth::fromUser($user);
        return response()->json(['status_code' => 200, 'data' => $user, 'token' => $token], 200);
    }
    /**
     * Get Authenticated User
     */
    public function getAuthenticatedUser(Request $request)
    {
        $user = \Auth::user();
        $radius = $request->get('radius', 100);
        $users = User::nearBy(['longitude'=> $user->longitude, 'latitude'=>
        $user->latitude], $radius)
        ->has('video')->get();

        return response()->json([
            'status_code'  => 200,
            'data'         => ['users' => $user,'nearby_users' => $users->count()]
        ], 200);
    }
    
    /**
     * Reset Password
     */
    public function setPassword(SetPasswordRequest $request)
    {
        $user = User::where(['email' => $request->get('email'), 'remember_token' => $request->get('token')])->first();
        if (!$user) {
            throw new \Exception("Sorry, please try again later.", 400);
        } else {
            $user->update(['remember_token' => '', 'password' => bcrypt($request->get('password'))]);
            $user['subject'] = 'Reset Password';
            $user['meg']     = 'Your password successfully updated.';
            $user->notify(new UserNotification($user));
            return response()->json([
                'status_code' => '200',
                'message'     => 'Your password successfully updated.',
            ]);
        }
    }
    /**
     * Update Profile
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "token" : "$token"
     * }
     */
    public function updateProfile(Request $request)
    {
        $user = \Auth::user();
        if ($user) {
            $fields = ['name', 'phone', 'dob', 'country', 'profile_pic', 'address', 'latitude', 'longitude', 'gender' ];
            foreach ($fields as $key => $field) {
                if ($request->exists($field)) {
                    switch ($field) {
                        case 'dob':
                        $user->$field = date('Y-m-d', strtotime($request->dob));
                        break;
       
                        default:
                        $user->$field = $request->$field;
                        break;
                    }
                }
            }
            $user->save();
            return response()->json([
                'status_code'   => 200,
                'data'          => $user,
                'message'       => 'User has been Updated successfully'
            ]);
         
        } else {
            return response()->json([
                'status_code' => 401,
                'message'     => 'Invalid user.',
            ], 401);
        }
    }

    /**
     * Change Password
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "token" : "$token"
     * }
     */
    public function changePassword(ChangePasswordRequest $request)
    {

        $user = \Auth::user();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['status_code' => 200, 'message' => 'Password has been updated.'], 200);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Entered old password is incorrect.'], 400);
            }

        } else {
            return response()->json([
                'status_code' => 401,
                'message'     => 'Invalid user.',
            ], 401);
        }
        
    }

    /**
     * Near By User
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     * }
     */
    public function nearByUser(NearByUserRequest $request)
    {
        $post = $request->all();
        $user = \Auth::user();
        $latlng  = $request->only(['longitude', 'latitude']);
        $radius  = $request->get('radius', 100);
        $users = User::nearBy($latlng, $radius)->has('video')->get();
        
        return response()->json([
            'status_code' => 200,
            'data'        => $users,
        ]);
    }
}
