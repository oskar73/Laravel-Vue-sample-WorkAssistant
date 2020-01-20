<?php

namespace App\Models;

/**
 * Class DomainPrice.
 *
 * @package namespace App\Models;
 */
class DomainPrice extends BaseModel
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'domain_prices';

    public function getTotalPriceAttribute()
    {
        return formatNumber($this->YourPrice + $this->YourAdditonalCost + $this->addPrice);
    }

    public function getSumPriceAttribute()
    {
        $sumPrice = 0;
        $prices = $this->where('Action', $this->Action)->where('tld', $this->tld)->where('Duration', '<=', $this->Duration)->get();

        foreach ($prices as $price) {
            $sumPrice += $price->totalPrice;
        }

        return formatNumber($sumPrice);
    }

    public function switchStatus($request)
    {
        if ($request->obj == 'tld') {
            $tld = new DomainTld();
            $tld->where('id', $request->id)->update([$request->action => $request->value]);
        } elseif ($request->obj == 'price') {
            $this->where('id', $request->id)->update(['status' => $request->value]);
        }
    }
    public function updateRule()
    {
        $rule['addPrice'] = 'required|regex:/^\d+(\.\d{1,2})?$/';
        $rule['Action'] = 'in:register,renew,transfer,reactivate';

        return $rule;
    }
    public function switchRule()
    {
        $rule['obj'] = 'required|in:tld,price';
        $rule['value'] = 'required|integer';
        $rule['action'] = 'in:register,renew,transfer,reactivate,status,recommend';

        return $rule;
    }
}
