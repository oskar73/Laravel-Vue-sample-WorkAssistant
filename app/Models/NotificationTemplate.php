<?php

namespace App\Models;

use App\Events\BasicNotificationEvent;
use App\Jobs\SendEmailJob;
use App\Mail\BasicMail;
use Yajra\DataTables\Facades\DataTables;

class NotificationTemplate extends BaseModel
{
    protected $table = "notification_templates";

    protected $guarded = ["id", "created_at", "updated_at"];

    const CUSTOM_VALIDATION_MESSAGE = [

    ];
    public const ORDER_CONFIRM_USER = 'order_confirm_user';
    public const ORDER_CONFIRM_ADMIN = 'order_confirm_admin';
    public const EMAIL_VERIFICATION = 'email_verification';
    public const WELCOME_EMAIL = 'welcome_email';
    public const FORGOT_PASSWORD = 'forgot_password';
    public const PROFILE_CHANGED = 'profile_changed';
    public const INVOICE_ADMIN = 'invoice_admin';
    public const INVOICE_USER = 'invoice_user';
    public const CANCEL_SUBSCRIPTION_USER = 'cancel_subscription_user';
    public const CANCEL_SUBSCRIPTION_ADMIN = 'cancel_subscription_admin';
    public const BLOG_POST_APPROVAL = 'blog_post_approval';
    public const BLOG_POST_APPROVED = 'blog_post_approved';
    public const BLOG_POST_DENIED = 'blog_post_denied';
    public const BLOG_ADS_APPROVAL = 'blog_ads_approval';
    public const BLOG_ADS_APPROVED = 'blog_ads_approved';
    public const BLOG_ADS_DENIED = 'blog_ads_denied';
    public const BLOG_COMMENT_APPROVAL = 'blog_comment_approval';
    public const APPOINTMENT_APPROVAL = 'appointment_approval';
    public const APPOINTMENT_APPROVED = 'appointment_approved';
    public const APPOINTMENT_CANCELED = 'appointment_canceled';
    public const APPOINTMENT_RESCHEDULED = 'appointment_rescheduled';
    public const APPOINTMENT_CANCELED_BY_USER = 'appointment_canceled_by_user';
    public const TICKET_NEW = 'ticket_new';
    public const TICKET_REPLIED = 'ticket_replied';
    public const TICKET_REPLIED_BY_USER = 'ticket_replied_by_user';
    public const PURCHASE_FOLLOWUP_APPROVAL = 'purchase_followup_approval';
    public const PURCHASE_FOLLOWUP_NEED_REVISION = 'purchase_followup_need_revision';
    public const PURCHASE_FOLLOWUP_COMPLETED = 'purchase_followup_completed';
    public const BLOG_NOTIFY_TO_AUTHOR_SUBSCRIBERS = 'blog_notify_to_author_subscribers';
    public const BLOG_NOTIFY_TO_CATEGORY_SUBSCRIBERS = 'blog_notify_to_category_subscribers';
    public const BLOG_NOTIFY_TO_POST_SUBSCRIBERS = 'blog_notify_to_post_subscribers';
    public const BLOG_COMMENT_TO_SUBSCRIBERS = 'blog_comment_to_subscribers';
    public const DOMAIN_EXPIRED = 'domain_expired';
    public const DOMAIN_EXPIRED_SOON = 'domain_expired_soon';
    public const CAMPAIGN_SENT = 'campaign_sent';
    public const APPOINTMENT_APPROVAL_REMIND = 'appointment_approval_remind';
    public const APPOINTMENT_REMIND = 'appointment_remind';
    public const BLOG_ADS_EXPIRED = 'blog_ads_expired';
    public const BLOG_ADS_PAID_REMIND = 'blog_ads_paid_remind';
    public const BLOG_ADS_EXPIRE_REMIND = 'blog_ads_expire_remind';
    public const REVIEW_NOTIFY = 'review_notify';
    public const REVIEW_APPROVED = 'review_approved';
    public const PORTFOLIO_APPROVAL = 'portfolio_approval';
    public const DIRECTORY_APPROVAL = 'directory_approval';
    public const DIRECTORY_EDITED_APPROVAL = 'directory_edited_approval';

    public const NEWSLETTER_ADS_APPROVAL = 'newsletter_ads_approval';

    public const NEWSLETTER_ADS_APPROVED = 'newsletter_ads_approved';

    public const NEWSLETTER_ADS_DENIED = 'newsletter_ads_denied';

    public const NEWSLETTER_ADS_EXPIRED = 'newsletter_ads_expired';

    public const NEWSLETTER_ADS_PAID_REMIND = 'newsletter_ads_paid_remind';

    public const NEWSLETTER_ADS_EXPIRE_REMIND = 'newsletter_ads_expire_remind';

    public function sendEmail($data, $slug, $email)
    {
        $template = $this->where("slug", $slug)->first();

        if ($template != null) {
            $body = $template->body;
            $unsubLink = \URL::signedRoute('unsubscribe');
            $data['unsubscribe'] = $unsubLink;
            foreach ($data as $key => $item) {
                $body = str_replace('{' . $key . '}', $item, $body);
            }

            $fromMail = $template->fromMail ?? 'info';
            $data2['body'] = $body;
            $data2['subject'] = $template->subject;
            $data2['fromName'] = $template->fromName ?? 'Bizinabox';
            $data2['fromMail'] = $fromMail . "@bizinabox.com";

            $object['email'] = $email;
            $object['object'] = new BasicMail($data2);

            dispatch(new SendEmailJob($object));
        }
    }

    public function sendNotification($data, $slug, $user)
    {
        $template = $this->where("slug", $slug)->first();
        if ($template != null) {
            $body = $template->body;
            $data['unsubscribe'] = \URL::signedRoute('unsubscribe');
            foreach ($data as $key => $item) {
                $body = str_replace('{' . $key . '}', $item, $body);
            }

            $fromMail = $template->fromMail ?? 'info';

            $data2['body'] = $body;
            $data2['url'] = $data['url'] ?? '';
            $data2['subject'] = $template->subject;
            $data2['fromName'] = $template->fromName ?? 'Bizinabox';
            $data2['fromMail'] = $fromMail . "@bizinabox.com";

            event(new BasicNotificationEvent($user, $data2));
        }
    }

    public function sendBothNotification($data)
    {
        $data1['username'] = user()->name;
        $data1['url'] = $data['url_user'];

        $admin = User::find(1);
        $data2['username'] = $admin->name;
        $data2['user'] = user()->name;
        $data2['url'] = $data['url_admin'];

        $this->sendNotification($data1, $data['slug_user'], user());
        $this->sendNotification($data2, $data['slug_admin'], $admin);
    }

    public function sendNotificationToAdmin($data)
    {
        $admin = User::find(1);
        $data['username'] = $admin->name;
        if (!isset($data['user'])) {
            $data['user'] = user()->name ?? '';
        }

        $this->sendNotification($data, $data['slug'] ?? '', $admin);
    }

    public function storeRule($request)
    {
        $rule['category'] = 'required';
        $rule['name'] = 'required';
        if ($request->template_id) {
            $rule['slug'] = 'required|unique:notification_templates,slug,' . $request->template_id . ',id';
        } else {
            $rule['slug'] = 'required|unique:notification_templates';
        }
        $rule['subject'] = 'nullable|max:191';
        $rule['fromMail'] = 'required|max:191';
        $rule['fromName'] = 'required|max:191';
        $rule['body'] = 'required';

        return $rule;
    }

    public function storeItem($request)
    {
        $item = $this;
        $item->category_id = $request->category;
        $item->subject = $request->subject;
        $item->slug = $request->slug;
        $item->fromMail = $request->fromMail;
        $item->fromName = $request->fromName;
        $item->name = $request->name;
        $item->body = $request->body;
        $item->save();

        return $item;
    }

    public function category()
    {
        return $this->belongsTo(NotificationCategory::class, 'category_id')->withDefault();
    }

    public function getDatatable($request)
    {
        if ($request->category == 0) {
            $templates = $this::with('category');
        } else {
            $templates = $this::with('category')->where("category_id", $request->category);
        }

        return Datatables::of($templates)->addColumn('category', function ($row) {
            return $row->category->name;
        })->addColumn('action', function ($row) {
            return '<a href="' . route('admin.notification.template.show', $row->id) . '" class="btn btn-outline-info btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-id="' . $row->id . '">
                        <span>
                            <i class="la la-eye"></i>
                            <span>Detail</span>
                        </span>
                    </a>
                    <a href="' . route('admin.notification.template.edit', $row->id) . '" class="btn btn-outline-success btn-sm m-1	p-2 m-btn m-btn--icon editBtn" data-id="' . $row->id . '">
                        <span>
                            <i class="la la-edit"></i>
                            <span>Edit</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm m-1	p-2 m-btn m-btn--icon switchOne" data-action="delete"  data-id="' . $row->id . '">
                        <span>
                            <i class="la la-remove"></i>
                            <span>Delete</span>
                        </span>
                    </a>';
        })->rawColumns(['action'])->make(true);
    }
}
