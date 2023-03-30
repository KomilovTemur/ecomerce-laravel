<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
  public function index(Request $request)
  {
    $keyword = $request->get('search');
    $perPage = 5;

    $product = Product::orderby("created_at")->get();
    if (!empty($keyword)) {
      $product = Product::where('name', "LIKE", "%$keyword")
        ->orWhere('category', "LIKE", "%$keyword")->latest()->paginate($perPage);
    } else {
      $product = Product::latest()->paginate($perPage);
    }
    return view("products.index", ["products" => $product])->with('i', (request()->input('page', 1) - 1) * 6);
  }
  public function create()
  {
    return view("products.create");
  }
  public function store(Request $request)
  {

    $request->validate([
      'name' => "required",
      'image' => "required|image|mimes:jpg, png, jpeg, gif, svg|max:2028",
    ]);

    $file_name = time() . "." . request()->image->getClientOriginalExtension();
    $request->image->move(public_path('images'), $file_name);

    $product = new Product;
    $product->name = $request->name;
    $product->description = $request->description;
    $product->image = $file_name;
    $product->category = $request->category;
    $product->quantity = $request->quantity;
    $product->price = $request->price;

    $product->save();
    return redirect()->route('products.index')->with('success', 'Product Added successfuly');
  }
  public function edit($id) {
    $product = Product::findOrFail($id);
    return view("products.edit", ['product' => $product]);
  }
}
