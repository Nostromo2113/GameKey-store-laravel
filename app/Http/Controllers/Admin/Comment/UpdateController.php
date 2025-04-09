<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\UpdateRequest;
use App\Models\Comment;

class UpdateController extends Controller
{
    public function __invoke(Comment $comment, UpdateRequest $request)
    {
        $this->authorize('update', $comment);

        $data = $request->validated();

        $comment->update([
           'content' => $data['content']
        ]);

        return response()->json([
            'message' => 'Comment successfully updated',
            'data' => $comment
        ], 200);
    }
}
