<?php

namespace App\Traits;

trait HasUniqueValue
{
    public function getUniqueValue($field, $value, $filters = [])
    {
        $query = $this->where($field, 'LIKE', $value.'%');
        foreach ($filters as $key => $filter) {
            $query = $query->where($key, $filter);
        }
        $similar_values = $query->whereNot('id', $this->id)
            ->select($field)
            ->pluck($field)
            ->toArray();
        $count = 0;
        foreach ($similar_values as $similar_value) {
            if ($similar_value == $value || (in_array($value, $similar_values) && preg_match("/$value \(\d\)/", $similar_value))) {
                $count++;
            }
        }
        if ($count) {
            return $value.' ('.$count.')';
        }

        return $value;
    }
}
