<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct(){
        //$this->middleware('auth')->except([ 'show', 'home']);
        $this->middleware('auth:seller')->except(['show', 'home']);
    }

    public function home()
    {
        return view('customer.index', ['products' => Product::all()->sortByDesc('id'), 'shops' => Seller::all()]);
    }

    public function index()
    {
        $books = Product::all();
        return view('product.index', ['books' => $books]);
    }


    public function create()
    {
        $categoryList = Category::all();
        return view('product.create', ['category' => $categoryList]);
    }


    public function store(Request $request): RedirectResponse
    {
        try {
            $this->validate($request, ['image' => 'required|image|mimes:jpeg,png,jpg|max:1080']);
        } catch (ValidationException $e) {
            return redirect()->route('book.create')->with('message', 'Image upload failed');
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

        }

        $product = new Product;
        $product->book_name =  $request->book_name;
        $product->publisher_name =  $request->publisher_name;
        $product->writer_name =  $request->writer_name;
        $product->stock =  $request->stock;
        $product->price =  $request->price;
        $product->category_id =  $request->category_id;
        $product->image =  $name;
        $product->description =  $request->description;
        $product->save();

        return redirect()->route('book.create')->with('message', 'Post Create Success');


    }

    public function show($id)
    {
        return view('product.show', ['data' => Product::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit', ['product' => $product, 'category' => Category::all()]);
    }

    public function update(Request $request, Product $product, $id)
    {
        $attribute = $request->validate([
            'book_name' => 'required',
            'publisher_name' => 'required',
            'writer_name' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'category_id' => 'required',
        ]);
        $product = Product::findOrFail($id);
        $product->update($attribute);
        return redirect()->route('book.edit', $id)->with('message', 'Update Success');
    }


    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('book.index')->with('delete', 'Delete Success');
    }
}
