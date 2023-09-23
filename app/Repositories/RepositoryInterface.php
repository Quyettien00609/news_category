<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function all($columns = ["*"]);

    public function create($data = []);

    public function update($data = [], $id, $attribute = 'id');

    public function delete($id);

    public function find($id, $columns = ['*']);

    public function findBy($field, $value, $columns = ['*']);
}
