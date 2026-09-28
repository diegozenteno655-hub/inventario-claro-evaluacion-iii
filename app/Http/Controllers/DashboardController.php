<?php
namespace App\Http\Controllers;
use App\Models\Product;
class DashboardController extends Controller {
    public function __invoke() {
        return view('dashboard', [
            'total' => Product::count(),
            'units' => Product::sum('quantity'),
            'low' => Product::whereColumn('quantity', '<=', 'minimum_stock')->count(),
            'value' => Product::selectRaw('COALESCE(SUM(quantity * price), 0) as total')->value('total'),
            'alerts' => Product::whereColumn('quantity', '<=', 'minimum_stock')->orderBy('quantity')->limit(6)->get(),
        ]);
    }
}
