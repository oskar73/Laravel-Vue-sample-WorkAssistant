<?php

namespace App\Models;

class Todo extends BaseModel
{
    protected $table = 'todos';

    protected $guarded = ['id', 'created_at', 'updated_at'];


    const CUSTOM_VALIDATION_MESSAGE = [

    ];

    public static function storeItem($user, $type, $model_type, $model_id, $order_item_id, $count = 1)
    {
        $todo = new Todo();
        $todo->type = $type;
        $todo->model_type = $model_type;
        $todo->model_id = $model_id;
        $todo->order_item_id = $order_item_id;
        $todo->count = $count;
        $todo->user_id = $user->id;
        $todo->save();

        return $todo;
    }

    public function todoItem()
    {
        return $this->morphTo('todoItem', 'model_type', 'model_id', 'id')->withDefault();
    }
    public function getLabel()
    {
        $type = $this->type;
        switch ($type) {
            case "appointment":
                $result = 'Make Appointment';

                break;
            case "blog":
                $result = "Add Blog Post";

                break;
            case "blogAds":
                $result = "Submit Ads Listing";

                break;
            case "website":
                $result = "Create Website";

                break;
            case "domain":
                $result = "Register Domain";

                break;
            case "form":
                $result = "Submit Form";

                break;
            default:
                $result = "Do it now";

                break;
        }

        return $result;
    }
    public function getName()
    {
        try {
            $type = $this->type;

            switch ($type) {
                case "form":
                case "appointment":
                    $result = $this->todoItem->model->getName() ?? '';

                    break;
                case "website":
                case "domain":
                case "blog":
                    $result = $this->todoItem->getName();

                    break;
                case "blogAds":
                    $result = $this->todoItem->spot->name ?? '';

                    break;
                default:
                    $result = $type;

                    break;
            }

            return $result;
        } catch (\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return '';
        }
    }
    public function getUrl()
    {
        $type = $this->type;
        switch ($type) {
            case "appointment":
                $result = route("user.appointment.create");

                break;
            case "blog":
                $result = route("user.blog.create");

                break;
            case "blogAds":
                $result = route("user.blogAds.edit", $this->model_id);

                break;
            case "website":
                $result = route("user.website.getting.started");

                break;
            case "form":
                $result = route("user.purchase.form.edit", $this->model_id);

                break;
            default:
                $result = "Do it now";

                break;
        }

        return $result;
    }
}
