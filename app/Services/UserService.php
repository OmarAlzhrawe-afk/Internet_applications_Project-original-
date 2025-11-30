<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo->dao();
    }

    public function index()
    {
        return $this->repo->getUsers();
    }

    public function create(array $data)
    {
        if (auth('sanctum')->user()->role === 'supervisor' && $data['agency_id'] != auth('sanctum')->user()->agency_id) {
            return  sendResponse(null, 403, "You can't create a user in another agency.", false);
        }
        return $this->repo->createUser($data);
    }

    public function update(array $data)
    {

        return $this->repo->updateUser($data);
    }

    public function delete($id)
    {
        return $this->repo->deleteUser($id);
    }
}
