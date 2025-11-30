<?php

namespace App\Repositories;

use App\DAO\SuperAdminUserDAO;
use App\DAO\SupervisorUserDAO;
use App\DAO\EmployeeUserDAO;
use App\Models\User;

class UserRepository
{
    protected $dao;

    public function __construct()
    {
        $role = auth('sanctum')->user()->role;
        $this->dao = match ($role) {
            'super_admin' => new SuperAdminUserDAO(),
            'supervisor'  => new SupervisorUserDAO(),
            default       => abort(403, 'Unauthorized role'),
        };
    }

    public function dao()
    {
        return $this->dao;
    }
}
