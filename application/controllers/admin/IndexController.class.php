<?php
/**
 * Created by PhpStorm.
 * User: konghy
 * Date: 19-8-27
 * Time: 19-8-27
 */

class IndexController extends Controller {

    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = Loader::sigltion(Model::class);
    }

    public function index() {

    }
}