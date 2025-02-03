<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter berdasarkan seller yang sedang login
        $query->where('seller_id', auth()->id());

        // Filter nama
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filter harga
        if ($request->has('price') && $request->price) {
            $query->where('price', '<=', $request->price);
        }

        // Ambil data dengan pagination (10 per halaman)
        $products = $query->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        return view('seller.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Tambahkan seller_id dari user yang sedang login
        $validated['seller_id'] = auth()->id();

        Product::create($validated);
        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id) // Pakai $id di parameter
    {
        $product = Product::find($id);

        if (!$product) {
            // Kalau data nggak ketemu, lempar error atau redirect
            return redirect()->route('seller.products.index')->with('error', 'Produk tidak ditemukan.');
        }

        return view('seller.products.edit', compact('product'));
    }


    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('seller.products.index')->with('error', 'Produk tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }


    public function destroy(Product $id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
