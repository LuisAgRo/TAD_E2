<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with(['category', 'categories']);

        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;
            $query->where(function ($subQuery) use ($categoryId) {
                $subQuery->where('category_id', $categoryId)
                    ->orWhereHas('categories', function ($categoryQuery) use ($categoryId) {
                        $categoryQuery->where('categories.id', $categoryId);
                    });
            });
        }

        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $imageName);
            $data['image'] = 'img/' . $imageName;
        }

        $product = Product::create($data);
        $product->categories()->sync([$data['category_id']]);

        return redirect()->route('products.index')
            ->with('mensaje', 'Producto creado correctamente');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'sku'         => 'required|string|unique:products,sku,'.$id,
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $imageName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('img'), $imageName);
        $data['image'] = 'img/' . $imageName;
    }

        $product->update($data);
        $product->categories()->sync([$data['category_id']]);

        return redirect()->route('products.index')
            ->with('mensaje', 'Producto actualizado correctamente');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);


        if ($product->image) {
        $imagePath = public_path($product->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

        $product->delete();

        return redirect()->route('products.index')
            ->with('mensaje', 'Producto eliminado correctamente');
    }
    public function show($id)
{
    $product = Product::findOrFail($id);
    return view('products.show', compact('product'));
}
}

