<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VideoReportRequest as StoreRequest;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\VideoReportRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

/**
 * Class VideoReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class VideoReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
         */
        $this->crud->setModel('App\Models\VideoReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/videoreport');
        $this->crud->setEntityNameStrings('videoreport', 'Video Reports');

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
                'name'      => 'video_id',
                'label'     => 'Video',
                'type'      => 'video',
                'entity'    => 'video',
                'image'     => 'thumb_image',
                'attribute' => 'video',
            ],
        ]);

        // add asterisk for fields that are required in VideoReportRequest
        // $this->crud->setRequiredFields(StoreRequest::class, 'create');
        // $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
