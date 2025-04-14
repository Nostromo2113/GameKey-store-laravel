<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class ShowController extends Controller
{
    public function __invoke(Comment $comment)
    {
        return $comment;
    }
}
