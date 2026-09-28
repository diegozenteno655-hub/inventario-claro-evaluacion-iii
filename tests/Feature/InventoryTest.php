<?php
namespace Tests\Feature;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class InventoryTest extends TestCase {
    use RefreshDatabase;
    public function test_guest_cannot_access_internal_pages(): void {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/productos')->assertRedirect('/login');
        $this->post('/productos', [])->assertRedirect('/login');
    }
    public function test_login_and_logout(): void {
        User::create(['name'=>'Demo','email'=>'demo@test.local','password'=>'secret123']);
        $this->post('/login', ['email'=>'demo@test.local','password'=>'incorrecta'])->assertSessionHasErrors('email');
        $this->post('/login', ['email'=>'demo@test.local','password'=>'secret123'])->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
    public function test_product_creation_update_low_stock_and_dashboard(): void {
        $user = User::create(['name'=>'Demo','email'=>'demo@test.local','password'=>'secret123']);
        $this->actingAs($user)->post('/productos', ['name'=>'Tinta','sku'=>'TIN-001','category'=>'Oficina','quantity'=>2,'price'=>1200,'minimum_stock'=>5])->assertRedirect('/productos');
        $product = Product::firstOrFail();
        $this->assertDatabaseHas('products', ['sku'=>'TIN-001','quantity'=>2]);
        $this->actingAs($user)->get('/productos?filter=low')->assertSee('Tinta');
        $this->actingAs($user)->get('/dashboard')->assertSee('Tinta')->assertSee('Productos con alerta');
        $this->actingAs($user)->put('/productos/'.$product->id, ['name'=>'Tinta','sku'=>'TIN-001','category'=>'Oficina','quantity'=>9,'price'=>1200,'minimum_stock'=>5])->assertRedirect('/productos');
        $this->assertDatabaseHas('products', ['sku'=>'TIN-001','quantity'=>9]);
        $this->actingAs($user)->get('/productos?filter=low')->assertDontSee('TIN-001');
    }
    public function test_rejects_invalid_or_duplicate_products(): void {
        $user = User::create(['name'=>'Demo','email'=>'demo@test.local','password'=>'secret123']);
        $valid = ['name'=>'Tinta','sku'=>'TIN-001','category'=>'Oficina','quantity'=>2,'price'=>1200,'minimum_stock'=>5];
        $this->actingAs($user)->post('/productos', $valid)->assertSessionHasNoErrors();
        $this->actingAs($user)->post('/productos', $valid)->assertSessionHasErrors('sku');
        $this->actingAs($user)->post('/productos', [...$valid, 'sku'=>'TIN-002', 'quantity'=>-1])->assertSessionHasErrors('quantity');
    }
}
