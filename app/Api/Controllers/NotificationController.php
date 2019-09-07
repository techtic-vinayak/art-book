<?php
namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @resource Contact
 */
class NotificationController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $notification = $user->unreadNotifications()->get();
        return response()->json([
                'status_code' => 200,
                'data'        => $notification,
        ]);
       
    }
}
