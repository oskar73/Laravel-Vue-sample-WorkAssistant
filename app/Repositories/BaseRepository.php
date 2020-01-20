<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository implements BaseRepositoryInterface
{
    public $model;

    public function __construct()
    {
        return $this->makeModel();
    }

    public function makeModel(): Model
    {
        // Create new clear instance if created
        if ($this->model instanceof Model) {
            return app(get_class($this->model));
        }

        $model = app($this->model());

        if (! $model instanceof Model) {
            throw new ModelNotFoundException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        $this->setModel($model);

        return $model;
    }

    public function model(): string
    {
        return $this->model;
    }

    public function setModel(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function pluck(string $column, string $key = null): mixed
    {
        return $this->model->pluck($column, $key);
    }

    public function save($data)
    {
        if ($data['id'] ?? null) {
            $model = $this->model->find($data['id']);
            $model->update($data);
        } else {
            $model = $this->model->create($data);
        }

        return $model;
    }

    public function first(string $field, $value)
    {
        $condition = ucfirst($field);

        if ($value) {
            return $this->model->{"where$condition"}($value)->first();
        }

        return null;
    }

    public function firstOrNew(array $attributes = [])
    {
        return $this->model->firstOrNew($attributes);
    }

    public function firstOrCreate(array $attributes = [])
    {
        return $this->model->firstOrCreate($attributes);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByField($field, $value = null, $columns = ['*'])
    {
        return $this->model->where($field, '=', $value)->get($columns);
    }

    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyConditions($where);

        return $this->model->get($columns);
    }

    public function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    public function all(array $columns = ['*']): mixed
    {
        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        return $results;
    }

    public function findWhereIn(string $field, array $values, array $columns = ['*'])
    {
        return $this->model->whereIn($field, $values)->get($columns);
    }

    public function findWhereNotIn(string $field, array $values, array $columns = ['*']): mixed
    {
        return $this->model->whereNotIn($field, $values)->get($columns);
    }

    public function create(array $data)
    {
        $model = $this->model->newInstance($data);
        $model->save();

        return $model;
    }

    public function update(array $data = [])
    {
        $this->model->fill($data);
        $this->model->save();

        return $this->model;
    }

    public function updateOrCreate(array $attributes, array $values = []): mixed
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        return $model->delete();
    }

    public function has($relation): self
    {
        $this->model = $this->model->has($relation);

        return $this;
    }

    public function with(array $relations)
    {
        $this->model = $this->model->with($relations);

        return $this;
    }

    public function withCount(array $relations): self
    {
        $this->model = $this->model->withCount($relations);

        return $this;
    }

    public function whereHas($relation, $closure): self
    {
        $this->model = $this->model->whereHas($relation, $closure);

        return $this;
    }

    public function hidden(array $fields): self
    {
        $this->model->setHidden($fields);

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->model = $this->model->orderBy($column, $direction);

        return $this;
    }

    public function visible(array $fields): self
    {
        $this->model->setVisible($fields);

        return $this;
    }

    public function sort(array $sortIds): self
    {
        foreach ($sortIds as $key => $id) {
            $item = $this->model->find($id);
            $item->order = $key + 1;
            $item->save();
        }

        return $this;
    }

    public function switch($action, $ids)
    {
        $items = $this->model->whereIn('id', $ids)->get();

        if ($action === 'active') {
            $items->each->update(['status' => 1]);
        } elseif ($action === 'inactive') {
            $items->each->update(['status' => 0]);
        } elseif ($action === 'featured') {
            $items->each->update(['featured' => 1]);
        } elseif ($action === 'unfeatured') {
            $items->each->update(['featured' => 0]);
        } elseif ($action === 'new') {
            $items->each->update(['new' => 1]);
        } elseif ($action === 'undonew') {
            $items->each->update(['new' => 0]);
        } elseif ($action === 'delete') {
            $this->model::destroy($ids);
        }
    }
}
