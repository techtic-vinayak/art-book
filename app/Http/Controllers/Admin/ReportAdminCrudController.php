<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportAdminRequest as StoreRequest;
use App\Http\Requests\ReportAdminRequest as UpdateRequest;

/**
 * Class ReportAdminCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportAdminCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportAdmin');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-admin');
        $this->crud->setEntityNameStrings('reportadmin', 'report to admin');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->removeAllButtons();

        $this->crud->addColumns([
            [
                'name'      => 'user_id',
                'label'     => "User",
                'type'      => 'select',
                'entity'    => 'user',
                'attribute' => 'name',
            ],[
                'name'      => 'art_id',
                'label'     => "Art",
                'type'      => 'select',
                'entity'    => 'art',
                'attribute' => 'title',
            ]
        ]);


        // add asterisk for fields that are required in ReportAdminRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
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
