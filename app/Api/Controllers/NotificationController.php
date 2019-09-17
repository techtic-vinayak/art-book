<?php
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Api\Requests\NotificationRequest;

/**
 * @resource Contact
 */
class NotificationController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $notification = $user->unreadNotifications()->orderBy('created_at','DESC')->get();
        // print_r($notification);
        // exit();

        $notifications = $notification->map(function ($item, $key) {
            $item['sender_data'] = User::find($item['data']['sender_id']);
            return $item;
        });

        return response()->json([
            'status_code' => 200,
            'data'        => $notifications,
        ]);
    }
    public function delete(NotificationRequest $request)
    {
        $user = \Auth::user();
		$notificationId = $request->input('notification_id');
        $notification = Notification::query();
        if(!empty($notificationId))
        {
        	$notification = $notification->where('id', $notificationId);
        } else {
        	$notification = $notification->where('notifiable_id', $user->id);
        }
        $notification = $notification->delete();
        return response()->json([
            'status_code' => 200,
            'message'     => "Notification clear successfully.",
        ]);
    }
}
