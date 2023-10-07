<?php
namespace App\Repositories;

use App\Models\Reader;
use Illuminate\Support\Facades\Hash;

class ReaderRepository extends BaseRepository {
    public function __construct(Reader $model)
    {
        parent::__construct($model);
    }
    public function createReader($attributes = [])
    {
        $attributes['password'] = Hash::make($attributes['password']);
        return $this->create($attributes);
    }

    public function getAllReader()
    {
        return Reader::all();
    }
    public function updateReader($id, $attributes = [])
    {
        return $this->update($id, $attributes);
    }
    public function updateReaderByAdmin($id, $attributes = [])
    {
        return $this->update($id, $attributes);
    }

    public function getReaderById($id)
    {
        return Reader::find($id);
    }
    public function deleteReader($id)
    {
        return $this->delete($id);
    }
}
