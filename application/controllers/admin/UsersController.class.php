<?php

/**
 * Class UsersController
 */
class UsersController extends Controller {

    /* @var $usersModel UsersModel */
    protected $usersModel;

    public function __construct()
    {
        parent::__construct();

        $this->usersModel = Loader::sigltion(UsersModel::class);
    }

    public function index() {
        $dd = $this->usersModel->getList();
    }

    public function add() {

    }

    public function edit() {

    }

    public function delete() {

    }

    public function getOne() {

    }
}