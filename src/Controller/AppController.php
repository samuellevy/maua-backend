<?php
namespace App\Controller;

use Cake\Controller\Controller;
use \Crud\Controller\ControllerTrait;

class AppController extends Controller {

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
    }
    
    public $components = [
        'RequestHandler',
        'Crud.Crud' => [
            'actions' => [
                'Crud.Index',
                'Crud.View',
                'Crud.Add',
                'Crud.Edit',
                'Crud.Delete'
            ],
            'listeners' => [
                'Crud.Api',
                'Crud.ApiPagination',
                'Crud.ApiQueryLog'
            ]
        ]
    ];
}