<?php

namespace App\Api\Controllers;

use App\Api\Requests\AddContactRequest;
use App\Api\Requests\GetPhoneContactsRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @resource Contact
 */
class ContactController extends Controller
{
    /**
     * Get contacts
     */
    public function index()
    {
        $contacts = \Auth::user()->contacts;
        return response()->json([
            'status_code' => 200,
            'data'        => $contacts,
        ], 200);
    }

    /**
     * Add Contact
     * @response {
     *  "status_code" : "200",
     *  "data" : "$user",
     *  "message" : "Contact successfully added."
     * }
     */
    public function store(AddContactRequest $request)
    {
        $contact_id = $request->get('contact_id');
        $user       = \Auth::user();
        $user->contacts()->attach($contact_id);
        $contact = $user->contacts()->wherePivot('contact_id', $contact_id)->first();
        return response()->json([
            'status_code' => 200,
            'data'        => $contact,
            'message'     => 'Contact successfully added.',
        ], 200);
    }

    /**
     * Get Contact
     */
    public function show($id)
    {
        $contact = \Auth::user()->contacts()->wherePivot('contact_id', $id)->first();
        return response()->json([
            'status_code' => 200,
            'data'        => $contact,
        ], 200);
    }

    /**
     * Update Contact
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Delete Contact
     * @response {
     *  "status_code" : "200",
     *  "message" : "Contact successfully deleted."
     * }
     */
    public function destroy($id)
    {
        \Auth::user()->contacts()->detach($id);
        return response()->json(['status_code' => 200, 'message' => 'Contact successfully deleted.'], 200);
    }

    /**
     * Get Phone Contacts
     * @response {
     *  "status_code" : "200",
     *  "data" : "$contact",
     *  "message" : "Contact successfully listed."
     * }
     */
    public function getPhoneContacts(GetPhoneContactsRequest $request)
    {
        $user            = \Auth::user();
        $contact_numbers = explode(",", $request->get('contact_numbers'));
        $contact_numbers = array_map(function ($number) {
            return substr($number, -10);
        }, $contact_numbers);

        $user    = \Auth::user();
        $contact = \Auth::user()->whereIn('phone', $contact_numbers)->where('id', '!=', $user->id)->get();

        return response()->json([
            'status_code' => 200,
            'data'        => $contact,
            //'message'     => 'Contact successfully added.',
        ], 200);
    }
}
