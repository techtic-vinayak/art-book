<?php

namespace App\Api\Controllers;

use App\Api\Requests\AddReplyRequest;
use App\Api\Requests\ListReplyRequest;
use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\User;
use App\Notifications\ReplyNotification;

/**
 * @resource Reply
 */
class ReplyController extends Controller
{
    /**
     * Add reply
     */
    public function addReply(AddReplyRequest $request)
    {
        $user = \Auth::user();
        if ($user) {
            $users     = User::where('id', $request->get('to_user_id'))->get();
            $add_video = array('user_id' => $user->id,
                'caption'                    => $request->get('caption'),
                'thumb_image'                => $request->file('thumb_image'),
                'video_type'                 => $request->get('video_type'),
                'video'                      => $request->file('video'),
                'video_id'                   => $request->get('video_id'),
                'to_user_id'                 => $request->get('to_user_id'),
                'video_duration'             => $request->get('video_duration'),
            );
            $video = Reply::create($add_video);
            \Notification::send($users, new ReplyNotification([
                'video' => $video,
                'user'  => $user,
            ]));
            if ($video) {
                return response()->json([
                    'status_code' => 200,
                    'data'        => $video,
                    'message'     => 'Reply posted successfully.'], 200);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Error while uploading video.'], 400);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * get list reply
     */
    public function getListReply(ListReplyRequest $request)
    {
        $user = \Auth::user();
        if ($user) {
            $reply_list = Reply::with('userInfo')
                ->where('video_id', $request->get('video_id'))->get()->toArray();

            return response()->json(['status_code' => 200,
                'data'                                 => $reply_list, 'count' => count($reply_list)], 200);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
}
