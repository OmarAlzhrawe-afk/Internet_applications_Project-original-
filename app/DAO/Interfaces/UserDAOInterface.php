<?php

namespace App\DAO\Interfaces;

interface UserDAOInterface
{
    public function getUsers();
    public function createUser(array $data);
    public function updateUser(array $data);
    public function deleteUser($id);
}
