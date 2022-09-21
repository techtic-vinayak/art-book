<?php
namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ArtRequest as StoreRequest;
use App\Http\Requests\ArtRequest as UpdateRequest;
use Auth;
/**
 * Class ArtCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ArtCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Art');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/art');
        $this->crud->setEntityNameStrings('art', 'arts');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        $this->crud->addField([
                                'label'     => "User",
                                'type'      => 'select2',
                                'name'      => 'user_id',
                                'entity'    => 'userInfo',
                                'attribute' => 'name',
                                'model'     => "App\Models\User",
                                'function' => function($q) {
                                    $q->where('id', '<>', \Auth::id());
                                }
        ]);

        $this->crud->addField([
                                'label'     => "Category",
                                'type'      => 'select2',
                                'name'      => 'category',
                                'entity'    => 'categoryData',
                                'attribute' => 'name',
                                'model'     => "App\Models\Category"
                            ]);

        $this->crud->addField([
                                'label'     => "Painting Size",
                                'type'      => 'select2',
                                'name'      => 'size',
                                'entity'    => 'sizeData',
                                'attribute' => 'size',
                                'model'     => "App\Models\PaintingSize"
                            ]);

        $this->crud->addField([
                                'name'  => 'about',
                                'label' => 'About',
                                'type'  => 'textarea',
                                'placeholder' => 'Your textarea text here'
                            ]);

        $this->crud->addField([
                                'name'  => 'subject',
                                'label' => 'Subject',
                                'type'  => 'textarea',
                                'placeholder' => 'Your textarea text here'
                            ]);

        $this->crud->addField([
                                'name'  => 'image',
                                'label' => 'Image',
                                'type'  => 'image'
                            ]);

        $this->crud->addColumns([
                [
                    'name'      => 'user_id',
                    'label'     => "User",
                    'type'      => 'select',
                    'entity'    => 'userInfo',
                    'attribute' => 'name',
                ],  [
                    'name'      => 'category',
                    'label'     => "Category",
                    'type'      => 'select',
                    'entity'    => 'categoryData',
                    'attribute' => 'name',
                    'model'     => "App\Models\Category"
                ],  [
                    'name'      => 'size',
                    'label'     => "Painting Size",
                    'type'      => 'select',
                    'entity'    => 'sizeData',
                    'attribute' => 'size',
                ], [
                    'name'   => 'image',
                    'label'  => "Image",
                    'type'   => 'image',
                    'height' => '70px',
                    'width'  => '70px',
                ],
        ]);
        // remove an array of columns from the stack
        $this->crud->removeColumns(['about', 'subject', 'material', 'art_gallery']);
        // add asterisk for fields that are required in ArtRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        //$request['user_id']  = 1;
        // your additional operations before save here
        $redirect_location   = parent::storeCrud($request);
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
