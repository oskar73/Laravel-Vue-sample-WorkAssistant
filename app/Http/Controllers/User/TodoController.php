<?php

namespace App\Http\Controllers\User;

use App\Repositories\UserRepository;

class TodoController extends UserController
{
    private UserRepository $repository;

    public static $typeNames = [
        'blogPost',
        'blogAdsListing',
        'newsletterAdsListing',
        'appointment',
        'appointmentReschedule',
        'ticket',
        'purchaseForm',
        'website',
        'domain',
    ];

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $types = $this->getTypes();
        foreach ($types as $key => $type) {
            if ($type) {
                return redirect()->route('user.todo.detail', $key);
            }
        }

        return view('user.todo.index');
    }

    public function getTodoCount()
    {
        return $this->jsonSuccess(collect((object)$this->getTypes())->sum());
    }

    public function detail($type)
    {
        if (!in_array($type, self::$typeNames)) {
            abort(404);
        }

        $count = $this->getCounts($type);
        if ($count == 0) {
            return redirect()->route('user.todo.index');
        }
        if (request()->wantsJson()) {
            $todos = $this->getTodos($type);

            return $this->jsonSuccess($todos);
        }
        $types = $this->getTypes();

        return view('user.todo.detail', compact("type", "types", "count"));
    }

    public function getTypes()
    {
        $types = [];
        foreach (self::$typeNames as $typeName) {
            $types[$typeName] = $this->getCounts($typeName) ?? null;
        }

        return $types;
    }

    public function getTodos($type)
    {
        return $this->repository->getTodos($type);
    }

    public function getCounts($type)
    {
        return $this->repository->getCounts($type);
    }
}
