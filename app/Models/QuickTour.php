<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickTour extends Model
{
    use HasFactory;

    protected $table = 'quick_tours';

    protected $guarded = ['created_at', 'updated_at'];

    const CUSTOM_VALIDATION_MESSAGE = [
        'targetID.required' => 'The targetID field is required.',
    ];

    public function storeRule($request)
    {
        $rule['title'] = 'required|string|max:45';
        $rule['description'] = 'required|max:600';
        $rule['targetID'] = 'required|string|max:45';

        return $rule;
    }
    public function storeItem($request)
    {
        if ($request->item_id == null) {
            $item = $this;
        } else {
            $item = $this->findorfail($request->item_id);
        }
        $item->title = $request->title;
        $item->description = $request->description;
        $item->targetID = $request->targetID;
        $item->order = $item->order ?? ($this->max('order') + 1); // Get max order value and increase it by 1
        $item->status = $request->status? 1: 0;
        $item->save();

        return $item;
    }

    public function getTargetIDs()
    {
        return [
            'todo-list' => 'Todo List',
            'dashboard' => 'Dashboard',
            'announcement' => 'Announcement',
            'graphic-designs' => 'Graphic Designs',
            'portfolio' => 'Portfolio',
            'purchase-management' => 'Purchase Management',
            'tickets' => 'Tickets',
            'tutorials' => 'Tutorials',
            'websites' => 'Websites',
            'setting' => 'Setting',
            'appointments' => 'Appointments',
            'blogs' => 'Bizinabox Blog',
            'blog-ads' => 'Blog Advertise',
            'domains' => 'Domain',
            'file-storage' => 'File Storage',
            'business-listings' => 'Free Business Listing',
            'getting-started-website' => 'Getting Started Website',
            'palettes' => 'Palettes',
            'themes' => 'Themes',
            'purchase-management' => 'Purchase Management',
        ];
    }
}
