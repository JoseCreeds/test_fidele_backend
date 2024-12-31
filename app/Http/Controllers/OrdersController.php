<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Product;

class OrdersController extends Controller
{
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',

        ]);

        $user = auth()->user();
        $totalPrice = 0;

        foreach ($validated['products'] as $item) {
            $product = Product::findOrFail($item['id']);

            if ($product->stock < $item['quantity']) {
                return response()->json(['message' => 'Stock insuffisant pour le produit: ' . $product->libelle], 400);
            }

            $totalPrice += $product->price * $item['quantity'];

            //Reduire le stock
            $product->update(['stock' => $product->stock - $item['quantity']]);
        }

        $order = new Orders;
        $order->client_id = $user->id;
        $order->total_price = $totalPrice;
        $order->status = 'pending';
        $order->save();

        foreach ($validated['products'] as $item) {
            $orderItems = new OrderItems;
            $orderItems->order_id = $order->id;
            $orderItems->product_id = $item['id'];
            $orderItems->quantity = $item['quantity'];
            $orderItems->price = Product::find($item['id'])->price;
            $orderItems->save();
        }

        return response()->json(['message' => 'Commande créée avec succès', 'order' => $order], 201);
    }

    //Historique des commandes de client

    public function orderHistory(Request $request)
    {
        $client = auth()->user();

        $orders = Orders::where('client_id', $client->id)
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'products' => $order->orderItems->map(function ($item) {
                    return [
                        'product_id' => $item->product->id,
                        'libelle' => $item->product->libelle,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->quantity * $item->product->price, // Calcul du sous-total
                    ];
                }),
            ];
        });
        return response()->json($formattedOrders, 200);
    }


    //Vendor commandes
    public function vendorOrders()
    {
        // Récupérez l'utilisateur connecté
        $vendor = auth()->user();

        // Récupérez les commandes associées aux produits du vendeur
        $orders = $vendor->products()
            ->with(['orderItems.orders.client', 'orderItems.product'])
            ->get()
            ->flatMap(function ($product) {
                return $product->orderItems->map(function ($orderItem) {
                    return $orderItem->orders;
                });
            })
            ->unique('id')
            ->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'client' => [
                        'id' => $order->client->id,
                        'firstname' => $order->client->firstname,
                        'lastname' => $order->client->lastname,
                        'email' => $order->client->email,
                    ],
                    'products' => $order->orderItems->map(function ($item) {
                        return [
                            'product_id' => $item->product->id,
                            'libelle' => $item->product->libelle,
                            'price' => $item->product->price,
                            'quantity' => $item->quantity,
                            'subtotal' => $item->price,
                        ];
                    }),
                ];
            })
            ->values();

        return response()->json($orders, 200);
    }
}
