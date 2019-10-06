<?php

/**
 * Class AdminModel
 * 管理员模型器
 */
class UsersModel extends Model {

    //定义表名
    protected $table = 'users';

    public function getList() {
        return $this->getAllData();
    }

    public function addUser() {

    }

    public function editUser() {

    }

    public function deleteUser() {

    }
}