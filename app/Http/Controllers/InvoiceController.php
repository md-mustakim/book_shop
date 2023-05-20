<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }


    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Factory|Application|View
     */
    public function create(Product $product)
    {
        $currentUser = Auth::guard('customer')->user()->address;
        return view('invoices.create', ['product' => $product, 'address' => $currentUser]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'address' => 'required'
        ]);
        $total_price = (int)$product->price * (int)$request->input('product_count');
        $attributes = array(
            'seller_id' => $product->seller_id,
            'customer_id' => Auth::guard('customer')->id(),
            'product_id' => $product->id,
            'product_count' => (int)$request->input('product_count'),
            'product_price' => (int)$product->price,
            'total_price' => $total_price,
            'getaway' => $request->input('getaway'),
            'address' => $request->input('address'),
            'order_id' => rand(100000, 999999)
        );

        Invoice::create($attributes);
        return redirect()->route('customer.profile');
    }

    /**
     * Display the specified resource.
     *
     * @param Invoice $invoice
     * @return Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Invoice $invoice
     * @return Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Invoice $invoice
     * @return Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice
     * @return Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
