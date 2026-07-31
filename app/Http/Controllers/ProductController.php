<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get(); // [cite: 104]
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company')->get();
        return view('products.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            
            'name'=>'required|min:3|max:255',
            
            'barcode'=>'required|unique:products,barcode',
            
            'supplier'=>'required|max:255',
            
            'price'=>'required|numeric|min:0',
            
            'cost'=>'required|numeric|min:0',
            
            'stock'=>'required|integer|min:0',
            
            'category'=>'required|max:100',
            
            'unit_of_measurement'=>'required',

            'photo'=>'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);


        $data = $request->except('photo');
        $data['photo'] = $request->file('photo')->store('products', 'public');

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Producto registrado con éxito.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::orderBy('company')->get();
        return view('products.edit', compact('product', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'                => 'required|min:3|max:255',
            'barcode'=>'required|unique:products,barcode,'.$product->id,
            'supplier'            => 'required|max:255',
            'price'               => 'required|numeric|min:0',
            'cost'                => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'category'            => 'required|max:100',
            'unit_of_measurement' => 'required',
            'photo'               => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            // Borrar la foto anterior solo si era un archivo subido (no una URL antigua)
            if ($product->photo
                && !str_starts_with($product->photo, 'http')
                && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }

            $data['photo'] = $request->file('photo')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado.');
    }
    public function buscarBarcode($barcode)
{

    $producto = Product::where('barcode',$barcode)->first();

    if(!$producto){

        return response()->json(null);

    }

    return response()->json($producto);

}
}
