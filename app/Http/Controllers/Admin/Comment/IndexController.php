<?php

namespace App\Http\Controllers\Admin\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Comment\IndexRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {

        $data = $request->validated();
        $userId = $data['id'];
        $type = $data['type'] ?? 'product';


        if($type === 'product') {
            $product = Product::find($data['id']);
            $comments = $product->comments;
            if (isset($data['query'])) {
                $query = $data['query'];
                $filteredComments = $product->comments()->where('product_title', 'like', '%' . $query . '%')->get();
                return $filteredComments;
            }
            return $comments;
        }
        if($type === 'user') {
            $user = User::find($userId);
            $comments = $user->comments;
            if (isset($data['query'])) {
                $query = $data['query'];
                $filteredComments = $user->comments()->where('product_title', 'like', '%' . $query . '%')->get();
                return $filteredComments;
            }
            return $comments;
        }

    }
}
