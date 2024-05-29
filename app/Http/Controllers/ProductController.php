<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function cart()
    {
        return view('products.checkout');
    }

    public function checkout()
    {
        //stripe key
        $stripe = new \Stripe\StripeClient("pk_test_51PK21y03gK3LJhEqnfmGeuH7Rq7MLy51m5ZI9SUzrWekRizW7UKm3LAy4KnYdvbr1A9sAaLLIBQfWehqIE10er84007lIzRSio");

        \Stripe\Stripe::setApiKey("pk_test_51PK21y03gK3LJhEqnfmGeuH7Rq7MLy51m5ZI9SUzrWekRizW7UKm3LAy4KnYdvbr1A9sAaLLIBQfWehqIE10er84007lIzRSio");

        //get all products
        $products = Product::all();
        // $products = Cart::content();
        // if($products->count() == 0){
        //     return redirect()->route('products.index')->with('error','No hay productos en el carrito');
        // }
        $lineItems = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => 1,
            ];
        }
        //add products to session
        $session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'ui_mode' => 'embedded',
            'return_url' => route('products.index'),

        ]);


    }

    public function success()
    {
        Cart::destroy();
        return view('products.checkout-success');
    }

    public function cancel()
    {
        Cart::destroy();
        return view('products.checkout-cancel');
    }


    public function addToCart(Request $request){
        $product = Product::find($request->id);
        Cart::add($product->id, $product->name, 1, $product->price);
        return back()->with('success',"$product->name ¡se ha agregado con éxito al carrito!");
    }



}
