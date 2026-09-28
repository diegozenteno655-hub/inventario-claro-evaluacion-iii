<?php
namespace Database\Seeders;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::updateOrCreate(['email' => 'demo@inventarioclaro.test'], ['name' => 'Usuario de prueba', 'password' => 'Demo2026!']);
        foreach ([
            ['Cuaderno universitario','CUA-001','Papelería',24,2490,8],
            ['Lápiz pasta azul','LAP-002','Papelería',5,450,10],
            ['Resma tamaño carta','RES-003','Oficina',12,4990,5],
            ['Marcador permanente','MAR-004','Oficina',3,1290,6],
            ['Carpeta plástica','CAR-005','Archivo',16,890,8],
        ] as [$name,$sku,$category,$quantity,$price,$minimum_stock])
            Product::updateOrCreate(['sku' => $sku], compact('name','category','quantity','price','minimum_stock'));
    }
}
