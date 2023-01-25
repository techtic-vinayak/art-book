<?php
namespace App\Api\Controllers;

use App\Models\User;
use App\Models\UserStatus;
use App\Http\Controllers\Controller;
use App\Api\Requests\BlockRequest;

/**
 * @resource Contact
 */
class UserStatusController extends Controller
{
    public function index()
    {
        $user = \Auth::user();

        if ($user) {
            $users = User::with('blockUsers')->where('id',$user->id)->get()->toArray();

            return response()->json([
                    'status_code' => 200,
                    'data'        => $users,
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    
    /**
     * Add video with share
     */
    public function blockUser(BlockRequest $request)
    {
        $user  = \Auth::user();

        if ($user) {
            
            $user = UserStatus::updateOrCreate(
                ['user_id' => $user->id,'block_user_id' => $request->get('block_user_id')],
                ['user_id' => $user->id,'block_user_id' => $request->get('block_user_id')]);

            return response()->json([
                    'status_code' => 200,
                    'data'        => $user,
                    'message'     => 'User blocked successfully.'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }

    public function unblockUser($id)
    {
        $user = \Auth::user();
        
        if ($user) {
            
            $user = UserStatus::where(['user_id' => $user->id,
                'block_user_id' => $id])->delete();

            return response()->json([
                'status_code'   => 200,
                'message'       => 'User unblocked successfully'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 401);
        }
    }
    
}
