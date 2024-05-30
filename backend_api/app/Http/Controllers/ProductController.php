<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        if ($products->count() > 0) {
            return response()->json([
                'status' => 200,
                'products' => $products
            ],200);
        } 
        else
        {
            return response()->json([
                'status' => 404,
                'Message' => 'No Records Found'
            ],404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'name' => 'required | string | max:191',
            'description' => 'required | string | max:191', 
            'category' => 'required | string | max:191'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validate->messages()
            ], 400);
        } else {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'quantity' => $request->quantity,
            ]); 

            if ($product) {
                return response()->json([
                    'status' => 200,
                    'message' => "Product Created Succesfully"
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Something went wrong"
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //
    }
}
