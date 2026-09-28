<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ProductController extends Controller {
    public function index(Request $request) {
        $query = Product::query();
        if ($request->filled('q')) {
            $term = $request->string('q')->toString();
            $query->where(fn ($q) => $q->where('name', 'like', '%'.$term.'%')->orWhere('sku', 'like', '%'.$term.'%'));
        }
        if ($request->query('filter') === 'low') $query->whereColumn('quantity', '<=', 'minimum_stock');
        return view('products.index', ['products' => $query->orderBy('name')->paginate(10)->withQueryString(), 'filter' => $request->query('filter')]);
    }
    public function create() { return view('products.form', ['product' => new Product]); }
    public function store(Request $request) {
        Product::create($this->validated($request));
        return redirect()->route('products.index')->with('success', 'Producto registrado correctamente.');
    }
    public function edit(Product $product) { return view('products.form', compact('product')); }
    public function update(Request $request, Product $product) {
        $product->update($this->validated($request, $product));
        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }
    private function validated(Request $request, ?Product $product = null): array {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:40', Rule::unique('products', 'sku')->ignore($product?->id)],
            'category' => ['required', 'string', 'max:80'],
            'quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'minimum_stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);
    }
}
