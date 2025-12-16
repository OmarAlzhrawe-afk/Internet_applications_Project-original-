<?php

namespace app\contracts;

interface GovermentAgencyInterface
{
    public function index();
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
