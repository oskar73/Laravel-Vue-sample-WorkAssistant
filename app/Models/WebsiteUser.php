<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class WebsiteUser extends BaseModel
{
    use Notifiable;

    protected $connection = 'mysql2';
    protected $table = 'users';

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function profileUpdateRule($id)
    {
        $rule['owner_email'] = 'required|email|max:45|unique:mysql2.users,email,'.$this->id.',id,web_id,' . $id;
        $rule['owner_password'] = 'nullable|string|min:8|max:45';

        return $rule;
    }

    public function avatar()
    {
        return "https://ui-avatars.com/api/?size=300&&name=New User";
    }

    public function balance()
    {
        return $this->hasOne(AccountBalance::class, 'user_id');
    }
}
