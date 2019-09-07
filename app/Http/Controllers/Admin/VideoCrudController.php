<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VideoRequest as StoreRequest;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VideoRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class VideoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VideoCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
         */
        $this->crud->setModel('App\Models\Video');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/video');
        $this->crud->setEntityNameStrings('video', 'videos');
        $this->crud->setDefaultPageLength(10);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
         */

        // TODO: remove setFromDb() and manually define Fields and Columns
        // $this->crud->setFromDb();
        $this->crud->denyAccess(['create', 'update']);
        $this->crud->addColumns([
            [
                'name'      => 'user_id',
                'label'     => "User",
                'type'      => 'select',
                'entity'    => 'user',
                'attribute' => 'name',
            ], [
                'name'  => 'caption',
                'label' => "Caption",
            ]/*,[
            'name' => "thumb_image",
            'label' => "Thumb image",
            'type' => 'image',
            'upload' => true,
            ]*/, [
                'name'  => 'video',
                'label' => 'Video ',
                'type'  => 'video',
                'image' => 'thumb_image',

            ], [
                'name'  => 'Video Views',
                'label' => 'Video Views',
                'type'  => 'relation_count',
                'entity'    => 'views',

            ], [
                'name'  => 'Video Reply',
                'label' => 'Video Reply',
                'type'  => 'relation_count',
                'entity'
                => 'replies',

            ], [
                'name'   => 'Video Report',
                'label'  => 'Video Report',
                'type'   => 'relation_count',
                'entity' => 'reports',

            ],
        ]);
        // add asterisk for fields that are required in VideoRequest
        //$this->crud->setRequiredFields(StoreRequest::class, 'create');
        //$this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
