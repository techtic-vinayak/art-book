<?php
namespace App\Api\Controllers;

use App\Api\Requests\AddVideoReportRequest;
use App\Api\Requests\AddVideoRequest;
use App\Api\Requests\AddVideoViewRequest;
use App\Api\Requests\DeleteVideoRequest;
use App\Api\Requests\VideoListRequest;
use App\Http\Controllers\Controller;
use App\Models\Reply;
use App\Models\ShareVideo;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoReport;
use App\Models\VideoView;
use App\Notifications\AddVideoNotification;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

/**
 * @resource Contact
 */
class VideoController extends Controller
{
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status_code' => 400, 'message' => 'Invalid credentials'], 400);
            }
            $user = \Auth::user();
            // if (!$user) {
            //     throw new \Exception("Sorry Please verify your email address.", 400);
            // }
            $user->device_type = $request->get('device_type', '');
            $user->token       = $request->get('token', '');
            $user->timezone    = $request->get('timezone', 'UTC');
            $user->save();
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
     * Add video with share
     */
    public function addVideo(AddVideoRequest $request)
    {
        $contact_ids   = $request->get('contact_id');
        $contact_array = explode(',', $contact_ids);
        $user          = \Auth::user();
        if ($user) {
            $add_video = array('user_id' => $user->id,
                'caption'                    => $request->get('caption', ''),
                'thumb_image'                => $request->file('thumb_image', ''),
                'video_type'                 => $request->get('video_type', ''),
                'video'                      => $request->file('video', ''),
                'request_type'               => $request->get('request_type', ''),
                'video_duration'             => $request->get('video_duration'),
            );
            if ($request->get('request_type') == 'save') {
                if ($contact_ids) {
                    $users = User::whereIn('id', $contact_array)->get();
                    $check_exits_users = $users->count();
                    if (count($contact_array) == $check_exits_users) {
                        $video = Video::create($add_video);
                        foreach ($contact_array as $key => $value) {
                            $sharevideo = ShareVideo::create(
                                [
                                    'video_id'     => $video->id,
                                    'from_user_id' => $video->user_id,
                                    'to_user_id'   => $value,
                                ]
                            );
                        }
                        \Notification::send($users, new AddVideoNotification([
                            'video' => $video,
                            'user'  => $user
                        ]));
                        return response()->json([
                            'status_code' => 200,
                            'data'        => $video,
                            'message'     => 'Video posted successfully.'], 200);
                    } else {
                        return response()->json(['status_code' => 400, 'message' => 'Invalid contact id.'], 400);
                    }
                } else {
                    $video = Video::create($add_video);
                    if ($video) {
                        return response()->json([
                            'status_code' => 200,
                            'data'        => $video,
                            'message'     => 'Video posted successfully.'], 200);
                    } else {
                        return response()->json([
                            'status_code' => 400,
                            'message'     => 'Error while uploading video.'], 400);
                    }
                }
            } else {
                $video      = Video::create($add_video);
                $sharevideo = ShareVideo::create(
                    [
                        'video_id'     => $video->id,
                        'from_user_id' => $video->user_id,
                        'to_user_id'   => 0,
                    ]
                );
                return response()->json([
                    'status_code' => 200,
                    'data'        => $video,
                    'message'     => 'Video added successfully.'], 200);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Video list and sent,received list
     */
    public function videoList(VideoListRequest $request)
    {
        $type            = $request->get('type');
        $user_id         = $request->get('user_id');
        $user            = \Auth::user();
        $reported_videos = VideoReport::select('video_id')->where('user_id', $user->id)->get()->toArray();
        if ($user) {
            if (strtolower($type) == 'sent') {
                $video_list = Video::with('shareVideo.toUser')->whereHas('shareVideo', function ($q) use ($user) {
                    $q->where('from_user_id', $user->id);
                    $q->where('from_deleted_at', null);
                })->orderBy('id', 'desc')->get()->transform(function ($item) {

                    $item['to_user'] = $item->shareVideo->toUser;
                    unset($item->shareVideo->toUser);
                    return $item;
                });
            } elseif (strtolower($type) == 'received') {
                $video_list = Video::with('shareVideo.fromUser')->whereNotIn('id', $reported_videos)
                    ->whereHas('shareVideo', function ($q) use ($user) {
                        $q->where('to_user_id', $user->id);
                        $q->where('to_deleted_at', null);
                    })->orderBy('id', 'desc')->get()->transform(function ($item) {
                        $item['from_user'] = $item->shareVideo->fromUser;
                        unset($item->shareVideo->fromUser);
                        return $item;
                    });
            } else {
                if ($user_id) {
                    $video_list = Video::with('user')->where('user_id', $user_id)
                        ->where('video_type', 'public')->orderBy('id', 'desc')
                        ->get()->toArray();
                } else {
                    $video_list = Video::with('user')->whereNotIn('id', $reported_videos)
                        ->where('video_type', 'public')->orderBy('id', 'desc')
                        ->get()->toArray();
                }
            }
            foreach ($video_list as $key => $value) {
                $view_count                = VideoView::where('video_id', $value['id'])->count();
                $reply_count               = Reply::where('video_id', $value['id'])->count();
                $video_list[$key]['view']  = $view_count;
                $video_list[$key]['reply'] = $reply_count;
            }
            return response()->json(['status_code' => 200, 'data' => $video_list], 200);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Delete video
     */
    public function deleteVideo(DeleteVideoRequest $request)
    {
        $user = \Auth::user();
        $data = $request->all();
        $current_time = Carbon::now()->toDateTimeString();
        extract($data);
        if ($user) {
            $video_list = Video::where('id', $video_id)->get()->toArray();
            if ($video_list) {
                $video_query = Video::where('id', $video_id)->first();
                $video_data  = pathinfo($video_query['video']);
                if (isset($type)) {
                    if ($type == 'received') {
                        $share_video_query = ShareVideo::where('video_id', $video_id)
                            ->where('to_user_id', $user['id'])->update(['to_deleted_at' => $current_time]);
                    } elseif ($type == 'shared') {
                        $share_video_query = ShareVideo::where('video_id', $video_id)
                            ->where('from_user_id', $user['id'])->update(['from_deleted_at' => $current_time]);
                        if (file_exists(public_path('uploads/user/' . $video_data['basename']))) {
                            unlink(public_path('uploads/user/' . $video_data['basename']));
                        }
                    }
                    return response()->json(['status_code' => 200, 'message' => 'Video delete successfully.'], 200);
                    // $share_video = $share_video_query->get()->toArray();
                    // if (!empty($share_video)) {
                    //     $del_share_video = $share_video_query->delete();
                    // }
                }
                $video_delete = Video::where('id', $video_id)->delete();

                return response()->json(['status_code' => 200, 'message' => 'Video delete successfully.'], 200);
            } else {
                return response()->json(['status_code' => 400, 'message' => 'Invalid video id.'], 400);
            }
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Add Video view
     */
    public function addVideoView(AddVideoViewRequest $request)
    {
        $user = \Auth::user();
        if ($user) {
            $video_id = $request->get('video_id');
            $video    = Video::find($video_id);
            $video->views()->sync($user->id, false);

            return response()->json([
                'status_code' => 200,
                'count'       => $video->views->count(),
            ], 200);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Add Video Report
     */
    public function addVideoReport(AddVideoReportRequest $request)
    {
        $user = \Auth::user();
        if ($user) {
            $video_id = $request->get('video_id');
            $video    = Video::find($video_id);
            $video->reports()->sync($user->id, false);

            return response()->json([
                'status_code' => 200,
                'message'     => 'You have successfully reported.',
            ], 200);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
}
