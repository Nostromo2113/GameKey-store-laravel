<?php

namespace App\Http\Controllers\GeoPosition;

use App\Http\Controllers\Controller;
use App\Models\GeoPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        // Валидируем входные данные
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'coords' => 'required|string',
            'pid' => 'required|integer|min:0',
        ]);

        // Если валидация не прошла — возвращаем JSON с ошибками
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $geo = GeoPosition::create($validator->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'GeoPosition создана успешно',
            'data' => $geo
        ], 201);
    }
}
