<?php
namespace App\Api\Controllers;

use App\Models\Art;
use App\Api\Requests\EditArtRequest;
use App\Api\Requests\AddArtRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ArtNotification;

/**
 * @resource Contact
 */
class ArtController extends Controller
{
    public function index()
    {
        $user          = \Auth::user();
        if ($user) {
            $art = Art::where('user_id',$user->id)->orderBy('id','DESC')->get();
            return response()->json([
                    'status_code' => 200,
                    'data'        => $art,
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }

    /**
     * Add video with share
     */
    public function addArt(AddArtRequest $request)
    {
        $user          = \Auth::user();

        if ($user) {
            $data = array(
                'user_id'                    => $user->id,
                'title'                      => $request->get('title'),
                'image'                      => $request->file('image'),
                'category'                   => $request->get('category'),
                'size'                       => $request->get('size'),
                'art_gallery'                => $request->get('art_gallery'),
                'material'                   => $request->get('material'),
                'subject'                    => $request->get('subject'),
                'about'                      => $request->get('about'),
            );
            $art = Art::create($data);

            $users = $user->following()->with('followingUser')->get();
            foreach ($users as $user_data) {
                $details = [
                    'user_id' => $user_data->receiver_id,
                    'sender_id' => $user->id,
                    'msg' => $user->name .' added '.$request->get('title').'  art.',
                ];
                $user_data->followingUser->notify(new ArtNotification($details));
            }
            return response()->json([
                    'status_code' => 200,
                    'data'        => $art,
                    'message'     => 'art posted successfully.'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Video list and sent,received list
     */
    public function editArt(EditArtRequest $request)
    {
        $user            = \Auth::user();
        if ($user) {
            $art = Art::find($request->id);
            $fields = ['title', 'image', 'category', 'size', 'art_gallery', 'material', 'subject', 'about'];
            foreach ($fields as $key => $field) {
                if ($request->exists($field)) {
                    switch ($field) {
                        default:
                        $art->$field = $request->$field;
                        break;
                    }
                }
            }
            $art->save();
            $art = Art::find($request->id);
            return response()->json([
                'status_code'   => 200,
                'data'          => $art,
                'message'       => 'Art has been Updated successfully'
            ]);

        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }
    /**
     * Delete video
     */
    public function detailArt($id)
    {
        $user = \Auth::user();
        if ($user) {
            $art = Art::with('userInfo')->where('id',$id)->first();
            return response()->json([
                'status_code'   => 200,
                'data'          => $art,
                'message'       => 'Art has been Updated successfully'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 401);
        }
    }

    public function deleteArt($id)
    {
        $user = \Auth::user();
        if ($user) {
            $art = Art::destroy($id);
            return response()->json([
                'status_code'   => 200,
                'message'       => 'Art has been deleted successfully'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 401);
        }
    }

    public function followingUserArt(Request $request)
    {
        $radius = !empty($request->input('radius')) ? $request->input('radius') :100;
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $user = \Auth::user();
        $user_data = $user->following();
        if(!empty($latitude) && !empty($longitude)){
            $user_data = $user_data->whereHas('followingUser', function ($query) use ($latitude,$longitude,$radius)
                {
                    $distance = "(
                        3959 * acos (
                          cos ( radians(".$latitude.") )
                          * cos( radians( latitude ) )
                          * cos( radians( longitude ) - radians(".$longitude.") )
                          + sin ( radians(".$latitude.") )
                          * sin( radians( latitude ) )
                        )
                      )";
                    $query->whereRaw($distance . "<= " . $radius);
                    $query->whereNotNull('latitude');
                    $query->whereNotNull('longitude');
                });
        }

        $user_data = $user_data->pluck('receiver_id')->toArray();

        $art = Art::whereIn('user_id', $user_data);
        $fields = ['title', 'art_gallery', 'size', 'category'];
        foreach ($fields as $field) {
            if ($request->exists($field)) {
                switch ($field) {
                    case 'size':
                    $art = $art->where('size', '=', $request->get($field));
                    break;

                    case 'category':
                    $art = $art->where('category', '=', $request->get($field));
                    break;

                    default:
                    $art = $art->where($field , 'LIKE',  '%' .$request->get($field). '%');
                    break;
                }
            }
        }

        $art = $art->orderBy('id','DESC')->get();

        return response()->json([
            'status_code'   => 200,
            'data'       => $art
        ]);

    }

}
