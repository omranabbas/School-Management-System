<?php

namespace App\Http\Controllers\Api;

use App\Models\Mark;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;

class MarkController extends Controller
{
    public function store(StoreMarkRequest $request)
    {
        $mark = Mark::create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Mark added successfully',
            'data' => $mark,
        ], 201);
    }
}