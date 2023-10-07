<?php
namespace App\Repositories;
use App\Repositories\BaseRepository;
use App\Models\Comment;
use Illuminate\Http\Request;
class CommentsRepository extends BaseRepository{
    public function __construct(Comment $model)
    {
        parent::__construct($model);
    }


}
