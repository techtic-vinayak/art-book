<?php
namespace App\Api\Controllers;

use App\Models\Art;
use App\Api\Requests\EditArtRequest;
use App\Api\Requests\AddArtRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ArtNotification;
use App\Models\Category;
use App\Models\PaintingSize;
use App\Models\ReportAdmin;
use App\Models\Connection;
use Auth;
/**
 * @resource Contact
 */
class ArtController extends Controller
{
    public function index(Request $request)
    {
        $user_id         = \Auth::id();
        $other_user_id = $request->input('other_user_id');

        if( isset($other_user_id) && !empty($other_user_id) )
        {
            $user_id = $other_user_id;
             $following = Connection::where('sender_id', $user_id)->where('status','accepted')->pluck('receiver_id')->toArray();
            $artist = array_merge([$user_id], $following);

            $art_id = Art::whereIn('user_id', $artist)->pluck('id');

            $arts = Art::with('reportAdminStop','paymentData')->with('userInfo')->doesnthave('reportAdmin')
            // ->whereHas('reportAdminStop', function ($query) {
                // $query->where('status','=','0');
            // })
            ->whereIn('id',$art_id)->get();
            if (count($arts) > 0) {
                $art = [];
                foreach ($arts as $checkart) {
                    if ($checkart->reportAdminStop) {
                        if ($checkart->paymentData) {
                            $art[] = $checkart;
                        }
                    } else {
                        $art[] = $checkart;
                    }
                }    
            } else {
                $art = [];
            }
        } else {
           $art = Art::doesnthave('reportAdminStop')->with('userInfo')->doesnthave('reportAdmin')
                ->where('user_id',$user_id)->orderBy('id','DESC')->get(); 
        }
      
        return response()->json([
            'status_code' => 200,
            'data'        => $art,
        ]);

    }

    /**
     * Add video with share
     */
    public function addArt(AddArtRequest $request)
    {
        $user          = \Auth::user();
        $category = Category::where('name',$request->get('category'))->value('id');
        $PaintingSize = PaintingSize::where('size',$request->get('size'))->value('id');
        if ($user) {
            $data = array(
                'user_id'                    => $user->id,
                'title'                      => $request->get('title'),
                'image'                      => $request->file('image'),
                'category'                   => $category,
                'size'                       => $PaintingSize,
                'art_gallery'                => $request->get('art_gallery'),
                'material'                   => $request->get('material'),
                'subject'                    => $request->get('subject'),
                'about'                      => $request->get('about'),
                'price'                      => floatval($request->get('price')),
            );
            $art = Art::create($data);
            $art['category'] =  Category::where('id',$art['category'])->value('name');
            $users = $user->following()->with('followingUser')->get();
            foreach ($users as $user_data) {
                $details = [
                    'user_id' => $user_data->receiver_id,
                    'sender_id' => $user->id,
                    'title' => 'Added Art',
                    'msg' => $user->name .' added '.$request->get('title').'  art.',
                ];
                $user_data->followingUser->notify(new ArtNotification($details));
            }
            return response()->json([
                    'status_code' => 200,
                    'data'        => $art,
                    'message'     => 'Art details posted successfully.'
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

        $request['category'] = Category::where('name',$request['category'])->value('id');
        $request['size'] = PaintingSize::where('size',$request['size'])->value('id');
        if ($user) {
            $art = Art::find($request->id);

            $fields = ['title', 'image', 'category', 'size', 'art_gallery', 'material', 'subject', 'about','price'];
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
                'message'       => 'Art details updated successfully.'
            ]);

        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 400);
        }
    }

    /**
     * Art Details
     */
   public function detailArt($id)
    {
        $user = \Auth::user();
        if ($user) {
            $art = Art::with('userInfo','paymentData')->join('report_admin','report_admin.art_id','=','art.id')->where('art.id',$id)->where('report_admin.status','=','0')->get();
            return response()->json([
                'status_code'   => 200,
                'data'          => $art
            ]); 
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 401);
        }
    }

    /**
     * Delete Art
     */
    public function deleteArt($id)
    {
        $user = \Auth::user();
        if ($user) {
            $art = Art::destroy($id);
            return response()->json([
                'status_code' => 200,
                'message'     => 'Art details deleted successfully.'
            ]);
        } else {
            return response()->json(['status_code' => 400, 'message' => 'Invalid user id.'], 401);
        }
    }

    /**
     * Following User Art
     */
    public function followingUserArt(Request $request)
    {
        $radius = !empty($request->input('radius')) ? $request->input('radius') : 100;
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

        $following = Connection::where('sender_id', $user->id)->where('status','accepted')->pluck('receiver_id')->toArray();
        $artist = array_merge([$user->id], $following);

        $art_id = Art::whereIn('user_id', $artist)->pluck('id');

        $art = Art::with('reportAdminStop')->with('userInfo','paymentData')->doesnthave('reportAdmin')
         ->whereIn('user_id',$user_data);
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

        $arts = $art->orderBy('art.id','DESC')->get();
        if (count($arts) > 0) {
             $art = [];
            foreach ($arts as $checkart) {
                if ($checkart->reportAdminStop) {
                    if ($checkart->paymentData) {
                        $art[] = $checkart;
                    }
                } else {
                    $art[] = $checkart;
                }
            }
        } else {
            $art = [];
        }


        if (count($arts) < 0) {
            return response()->json([
                'status_code' => 400,
                'data' => $art,
                'message' => 'No data found.Please follow some artist'
            ]);
        } else {
            return response()->json([
                'status_code' => 200,
                'data' => $art,
            ]);
        }
    }

}
