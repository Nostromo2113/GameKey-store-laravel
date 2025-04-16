<?php

namespace App\Http\Controllers\Admin\Product\ProductComment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\StoreRequest;
use App\Models\Comment;
use App\Models\Product;

class StoreController extends Controller
{
    public function __invoke(Product $product, StoreRequest $request)
    {
        $this->authorize('create', Comment::class);

        $comment = $product->comments()->create([
            'content'       => $request->input('content'),
            'user_id'       => auth()->id(),
            'user_name'     => auth()->user()->name,
            'product_title' => $product->title
        ]);

        return $comment;
    }
}
