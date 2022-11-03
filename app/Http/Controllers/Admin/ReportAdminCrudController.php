<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportAdminRequest as StoreRequest;
use App\Http\Requests\ReportAdminRequest as UpdateRequest;
use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Prologue\Alerts\Facades\Alert;

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
        $this->crud->addButtonFromView('line','reject','reject','end');
       
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->denyAccess(['create', 'update', 'delete']);

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
            ,[
                'name'      => 'status',
                'label'     => "Status",
                'type'  => 'boolean',
                'options' => [
                    0 => 'Rejected',
                    1 => 'Approved',
                ],

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

    public function approve($id)
    {
        $art_id = \App\Models\ReportAdmin::where('id',$id)->value('art_id');
        \App\Models\ReportAdmin::where('art_id',$art_id)->update(['status'=>'1']);
        //\App\Models\ReportAdmin::where('id',$id)->update(['status'=>'1']);
         Alert::success('Approved successfully')->flash();
         return \Redirect::back();
    }

    public function reject($id)
    {
        $art_id = \App\Models\ReportAdmin::where('id',$id)->value('art_id');
        \App\Models\ReportAdmin::where('art_id',$art_id)->update(['status'=>'0']);
        Alert::success('Reject successfully')->flash();
        return \Redirect::back();
    }

    //    protected function setupUpdateOperation()
    // {
    //     $this->crud->setValidation(UserEditRequest::class);
    //     $this->setupCreateOperation();
    //     $this->crud->removeField('status');
    //     $this->crud->addField([
    //          'name'        => 'status',
    //         'label'       => "Status",
    //         'type'        => 'select2_from_array',
    //         'options'     => [1 => 'Active', 0 => 'Inactive'],
    //         'allows_null' => false,
    //     ]);
   
    // }

}
