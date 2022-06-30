<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getAll()
    {
        $products = Product::all();
        return response()->json([
            'message' => 'Success get all products',
            'products' => $products
        ], 200);
    }

    public function getQuery(Request $request)
    {
        $query = Product::query();
        if ($search = $request->input('search')) {
            $query->whereRaw("title LIKE '%" . $search . "%'")
                ->orWhereRaw("description LIKE '%" . $search . "%'");
        }
        if ($sort = $request->input('sort')) {
            $query->orderBy('price', $sort);
        }
        $limit = $request->input('limit', 20);
        $page = $request->input('page', 1);
        $total = $query->count();
        $result = $query->offset(($page - 1) * $limit)->limit($limit)->get();
        $lastPage = ceil($total / $limit);
        return response()->json([
            'data' => $result,
            'limit' => ceil($limit),
            'total' => ceil($total),
            'page'  => ceil($page),
            'last_page' => $lastPage
        ], 200);
    }

    public function getDetail($id)
    {
        $product = Product::find($id);
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully get detail products',
            'data' => $product
        ], 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $valid = Validator::make($input, [
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'image' => 'required|string',
            'price' => 'required|integer',
        ]);

        if ($valid->fails()) {
            return response()->json([
                'message' => 'Error'
            ], 500);
        }

        $product = new Product;
        $product->title = $request->title;
        $product->description = $request->description;
        $product->image = $request->image;
        $product->price = $request->price;
        $product->save();
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully created',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'price' => $request->price,
        ]);
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully updated',
            'data' => $product
        ], 200);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully deleted'
        ], 200);
    }
}
