<?php

namespace App\contracts;

interface ComplaintManagmentInterface
{
    public function index();
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function add_comment_complaint($data);
    public function add_attachment_complaint($data);
    public function OneComplaint($id);
    public function accept_complaint($data);
}
