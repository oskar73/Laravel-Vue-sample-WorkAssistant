<?php


namespace App\Models\Extensions;

trait Sortable
{
    protected string $orderField = 'order';

    public function assignOrder($modify = null)
    {
        if ($modify) {
            $orderNumber = '#'.str_pad($modify, 20, "0", STR_PAD_LEFT);
        } else {
            $latestItem = self::orderBy('created_at', 'desc')->first();

            if ($latestItem) {
                $orderNumber = '#'.str_pad($latestItem->id + 1, 20, "0", STR_PAD_LEFT);
            } else {
                $orderNumber = '#'.str_pad(1, 20, "0", STR_PAD_LEFT);
            }
        }

        $this[$this->orderField] = $orderNumber;
    }

    public static function updateOrder($request): bool
    {
        $ids = explode(',', $request->ids);

        foreach ($ids as $key => $id) {
            $modelItem = self::find($id);

            $modelItem->assignOrder($key + 1);

            $modelItem->save();
        }

        return true;
    }
}
