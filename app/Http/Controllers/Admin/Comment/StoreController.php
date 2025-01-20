<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\StoreRequest;
use App\Models\Comment;

class StoreController extends Controller
{

    public function __invoke(StoreRequest $request)
    {
        return 'comment';
    }
}
