<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getTodos($type);
    public function getCounts($type);
}
