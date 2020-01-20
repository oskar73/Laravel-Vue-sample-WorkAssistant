<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppointmentList;
use App\Models\BlogAdsListing;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\DirectoryListing;
use App\Models\NewsletterAdsListing;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\UserForm;

class TodoController extends AdminController
{
    public $types;
    public static $typeNames = [
        'blogPost',
        'blogComment',
        'blogAdsListing',
        'newsletterAdsListing',
        'appointment',
        'ticket',
        'purchaseForm',
        'review',
        'portfolioApproval',
        'directoryApproval',
    ];

    public function __construct()
    {
        $types = [];
        foreach (self::$typeNames as $typeName) {
            $types[$typeName] = $this->getCounts($typeName) ?? null;
        }

        $this->types = $types;
    }

    public function index()
    {
        $types = $this->types;
        foreach ($types as $key => $type) {
            if ($type) {
                return redirect()->route('admin.todo.detail', $key);
            }
        }

        return view(self::$viewDir . 'todo.index');
    }

    public function getTodoCount()
    {
        return $this->jsonSuccess(collect((object)$this->types)->sum());
    }

    public function detail($type)
    {
        if (!in_array($type, self::$typeNames)) {
            abort(404);
        }

        $count = $this->getCounts($type);

        if ($count == 0) {
            redirect()->route('admin.todo.index');
        }

        if (request()->wantsJson()) {
            $todos = $this->getTodos($type);

            return $this->jsonSuccess($todos);
        }

        $types = $this->types;

        return view(self::$viewDir . 'todo.detail', compact("type", "types", "count"));
    }

    public function getTodos($type)
    {
        $result = "";
        $perPage = 10;

        switch ($type) {
            case "blogPost":
                $todos = BlogPost::with('user')->where("status", "pending")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.blogPost", compact("todos"))->render();

                break;
            case "blogComment":
                $todos = BlogComment::with('post', 'user')->where("status", "pending")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.blogComment", compact("todos"))->render();

                break;
            case "blogAdsListing":
                $todos = BlogAdsListing::with('user', 'spot')->where("status", "pending")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.blogAdsListing", compact("todos"))->render();

                break;
            case "appointment":
                $todos = AppointmentList::with('user')->where("status", "pending")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.appointment", compact("todos"))->render();

                break;
            case "ticket":
                $todos = Ticket::with('user')->where("parent_id", 0)->where("status", "opened")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.ticket", compact("todos"))->render();

                break;
            case "purchaseForm":
                $todos = UserForm::with('user')->where("status", "filled")->oldest()->paginate($perPage);
                $result = view("components.admin.todo.purchaseForm", compact("todos"))->render();

                break;
            case "review":
                $todos = Review::with('user', 'ratable')->where("status", 0)->oldest()->paginate($perPage);
                $result = view("components.admin.todo.review", compact("todos"))->render();

                break;
            case "portfolioApproval":
                $todos = Portfolio::whereNull("approved_at")->paginate($perPage);
                $result = view("components.admin.todo.portfolioApproval", compact("todos"))->render();

                break;
            case "directoryApproval":
                $todos = DirectoryListing::whereNull("approved_at")->paginate($perPage);
                $result = view("components.admin.todo.directoryApproval", compact("todos"))->render();

                break;
        }

        return $result;
    }

    public function getCounts($type)
    {
        $result = 0;
        switch ($type) {
            case "blogPost":
                $result = BlogPost::where("status", "pending")->count();

                break;
            case "blogComment":
                $result = BlogComment::where("status", "pending")->count();

                break;
            case "blogAdsListing":
                $result = BlogAdsListing::where("status", "pending")->count();

                break;
            case "newsletterAdsListing":
                $result = NewsletterAdsListing::where("status", "pending")->count();
                break;
            case "appointment":
                $result = AppointmentList::where("status", "pending")->count();

                break;
            case "ticket":
                $result = Ticket::where("parent_id", 0)->where("status", "opened")->count();

                break;
            case "purchaseForm":
                $result = UserForm::where("status", "filled")->count();

                break;
            case "review":
                $result = Review::where("status", 0)->count();

                break;
            case "portfolioApproval":
                $result = Portfolio::whereNull("approved_at")->count();

                break;
            case "directoryApproval":
                $result = DirectoryListing::whereNull("approved_at")->count();

                break;
        }

        return $result;
    }
}
