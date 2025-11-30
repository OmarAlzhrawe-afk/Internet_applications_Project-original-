<?php

namespace App\DAO\interfaces;

interface ComplaintDAOInterface
{
    public function find($id);
    public function getAll();
    public function update($id, array $data);
    public function delete($id);
}
