<?php

namespace App\Repositories;

use App\DAO\ComplaintDAO;
use App\DAO\SuperAdminUserDAO;
use App\DAO\SupervisorUserDAO;
use App\DAO\EmployeeUserDAO;
use App\DAO\interfaces\ComplaintDAOInterface;
use App\Models\User;

class ComplaintRepositry
{
    protected $dao;

    public function __construct(ComplaintDAO $dao)
    {
        $this->dao =  $dao;
    }

    public function dao()
    {
        return $this->dao;
    }
}
