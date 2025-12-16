<?php

namespace App\contracts;

interface UserManagingInterface
{
    public function index();
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
