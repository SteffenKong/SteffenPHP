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
        $data = [
            'user'=>'???',
            'pass'=>'???'
        ];

        echo serialize($data);
    }

    public function index2() {
        $unserialize_str = 'a:2:{s:4:"user";b:1;s:4:"pass";b:1;}';
        $data_unserialize = unserialize($unserialize_str);
        var_dump($data_unserialize);
        if($data_unserialize['user'] == '???' && $data_unserialize['pass']=='???')
        {
            print_r(123);
        }
    }
}