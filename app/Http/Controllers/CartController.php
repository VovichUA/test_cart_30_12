<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                $cart = Cart::query()->where('product_id', $request->get('product_id'))
                    ->where('user_id', Auth::id())
                    ->first();

                if ($cart) {
                    $cart->increment('quantity');
                    $cart->save();
                } else {
                    Cart::query()->create([
                        'product_id' => $request->get('product_id'),
                        'user_id' => Auth::id(),
                        'quantity' => 1
                    ]);
                }
            } else {

                $cart = session()->get('cart', []);
                if (isset($cart[$request->get('product_id')])) {
                    $cart[$request->get('product_id')]['quantity']++;
                } else {
                    $cart[$request->get('product_id')] = [
                        'name' => $request->get('name'),
                        'price' => $request->get('price'),
                        'quantity' => 1,
                        'product_id' => $request->get('product_id')
                    ];
                }
                session()->put('cart', $cart);
            }

            return response()->json([
                'success' => true,
                'message' => sprintf('%s added to cart!', $request->get('name')),
                'cart' => $cart
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'cart' => []
            ]);
        }
    }

    /**
     * @return View
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): View
    {
        $cart = Auth::check() ? Cart::query()->where('user_id', Auth::id())->get() : session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if (Auth::check()) {
            $cartItem = Cart::query()->find($id);
            $cartItem->update([
                'quantity' => $request->get('quantity')
            ]);
        } else {

            $cart = session()->get('cart');
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $request->get('quantity');
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index');
    }

    /**
     * @param $id
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function remove($id): RedirectResponse
    {
        if (Auth::check()) {
            Cart::query()->find($id)->delete();
        } else {
            $cart = session()->get('cart');
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->route('cart.index');
    }
}
