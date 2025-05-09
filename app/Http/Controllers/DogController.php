<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dogs;

class DogController extends Controller
{
    public function index()
    {
        return response()->json(Dogs::all());
    }

    public function show($id)
    {
        $dog = Dogs::all()->find($id);

        if (!$dog) {
            return response()->json(['message' => 'Dog not found']);
        }

        return response()->json($dog);
    }

    public function store(Request $request)
    {


        $request->validate([
            'age' => 'required|integer',
            'gender' => 'required|in:male,female',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|string'
        ]);
        $data = $request->only([
            'age',
            'gender',
            'price',
            'description',
            'status'
        ]);
        
        $data['user_id'] = auth()->id(); 
        
        $dog = Dogs::create($data);

        return response()->json(['message' => 'Dog added successfully', 'dog' => $dog]);
    }

    public function update(Request $request, $id)
    {
        $dog = Dogs::with(relations: 'owner')->find($id);

        if (!$dog) {
            return response()->json(['message' => 'Dog not found']);
        }

        if ($dog->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized']);
        }

        $request->validate([
            'age' => 'sometimes|integer',
            'gender' => 'sometimes|in:male,female',
            'price' => 'sometimes|numeric',
            'description' => 'sometimes|string',
            'status' => 'sometimes|string',
        ]);

        $dog->update($request->only(['age', 'gender', 'price', 'description', 'status']));

        return response()->json([
            'message' => 'Dog updated successfully',
            'dog' => $dog
        ]);
    }

    public function destroy($id)
    {
    $dog = Dogs::find($id);

    if (!$dog) {
        return response()->json(['message' => 'Dog not found']);
    }

    if ($dog->user_id !== auth()->id()) {
        return response()->json(['message' => 'Unauthorized']);
    }

    $dog->delete();

    return response()->json(['message' => 'Dog deleted successfully']);

    }
}