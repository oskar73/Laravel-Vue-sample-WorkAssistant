<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function makeModel(): Model;
    public function model();
    public function setModel(Model $model);
    public function pluck(string $column, string $key = null);
    public function switch($action, $ids);
    public function save($data);
    public function first(string $field, $value);
    public function firstOrNew(array $attributes = []);
    public function firstOrCreate(array $attributes = []);
    public function findByField($field, $value = null, $columns = ['*']);
    public function findWhere(array $where, $columns = ['*']);
    public function applyConditions(array $where);
    public function all(array $columns = ['*']);
    public function findWhereIn(string $field, array $values, array $columns = ['*']);
    public function findWhereNotIn(string $field, array $values, array $columns = ['*']);
    public function create(array $data);
    public function update(array $data = []);
    public function updateOrCreate(array $attributes, array $values = []);
    public function delete($id);
    public function has($relation);
    public function with(array $relations);
    public function withCount(array $relations);
    public function whereHas($relation, $closure);
    public function hidden(array $fields);
    public function orderBy(string $column, string $direction = 'asc');
    public function visible(array $fields);
    public function sort(array $sortIds);
}
