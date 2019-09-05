<?php
namespace App\Api\Controllers;

use App\Models\Art;
use App\Api\Requests\EditArtRequest;
use App\Api\Requests\AddArtRequest;
use App\Http\Controllers\Controller;

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
            $art = Art::find($id);
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
    
}
