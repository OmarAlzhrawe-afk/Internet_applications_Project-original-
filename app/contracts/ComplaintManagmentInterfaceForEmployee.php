<?php

namespace App\contracts;

interface ComplaintManagmentInterfaceForEmployee extends ComplaintManagmentInterface
{
    public function update($id, $data);
    public function add_comment_complaint($id, $data);
    public function add_attachment_complaint($id, $data);
}
