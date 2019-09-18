<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReportAdmin;
use App\Models\Art;
use App\Models\User;
use App\Notifications\ReportAdminNotification;

/**
 * @resource Reply
 */
class ReportAdminController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();

        if ($user) {
        	$art_id = $request->get('art_id');

        	$art = Art::find($art_id);

        	if(!$art){
        		return response()->json([
	        		'status_code' => 400,
	        		'message' => 'Art is not found.'
	        	], 400);
        	}

            $data = array(
				'user_id'     => $user->id,
				'art_id'      => $art_id,
				'description' => $request->get('description'),
            );

            ReportAdmin::create($data);

            $data['msg'] = $user->name.' has been reported for '.$art->title .' Art.';

            $admin = new User();
			$admin->email = env('MAIL_TO_ADDRESS') ? env('MAIL_TO_ADDRESS') : "admin@gmail.com";
            $admin->notify(new ReportAdminNotification($data));

         	return response()->json([
                'status_code' => 200,
                'message'     => 'Report to admin successfully.'
            ]);

        } else {

            return response()->json([
        		'status_code' => 400,
        		'message' => 'Invalid user id.'
        	], 400);

        }
    }
}
