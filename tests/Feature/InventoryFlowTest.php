<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created_with_valid_category_and_supplier(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama' => 'Minuman']);
        $supplier = Supplier::create([
            'nama_supplier' => 'PT Sumber Rejeki',
            'alamat' => 'Jl. Merdeka 1',
            'no_telp' => '08123456789',
            'email' => 'supplier@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('products.store'), [
            'nama_barang' => 'Teh Botol',
            'category_id' => $category->id,
            'harga_beli' => 5000,
            'harga_jual' => 7000,
            'supplier_id' => $supplier->id,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'nama_barang' => 'Teh Botol',
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);
    }

    public function test_product_creation_fails_with_invalid_foreign_keys(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(route('products.create'))->post(route('products.store'), [
            'nama_barang' => 'Gula Pasir',
            'category_id' => 99999,
            'harga_beli' => 10000,
            'harga_jual' => 12000,
            'supplier_id' => 99999,
        ]);

        $response->assertRedirect(route('products.create'));
        $response->assertSessionHasErrors(['category_id', 'supplier_id']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_stock_in_and_out_transactions_update_product_stock_accessor(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $this->actingAs($user)->post(route('products.stock.store', $product), [
            'type' => 'in',
            'quantity' => 15,
            'note' => 'Barang masuk',
        ])->assertRedirect(route('products.stock', $product));

        $this->actingAs($user)->post(route('products.stock.store', $product), [
            'type' => 'out',
            'quantity' => 4,
            'note' => 'Barang keluar',
        ])->assertRedirect(route('products.stock', $product));

        $product->refresh();

        $this->assertSame(11, $product->stock);
        $this->assertDatabaseCount('stock_transactions', 2);
    }

    public function test_stock_out_is_blocked_when_quantity_exceeds_current_stock(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        StockTransaction::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 3,
            'note' => 'Seed stock',
        ]);

        $response = $this->actingAs($user)
            ->from(route('products.stock', $product))
            ->post(route('products.stock.store', $product), [
                'type' => 'out',
                'quantity' => 10,
                'note' => 'Pengeluaran berlebih',
            ]);

        $response->assertRedirect(route('products.stock', $product));
        $response->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('stock_transactions', 1);
    }

    public function test_category_and_supplier_cannot_be_deleted_when_used_by_product(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $categoryResponse = $this->actingAs($user)
            ->delete(route('categories.destroy', $product->category_id));
        $categoryResponse->assertRedirect(route('categories.index'));
        $categoryResponse->assertSessionHas('error');

        $supplierResponse = $this->actingAs($user)
            ->delete(route('suppliers.destroy', $product->supplier_id));
        $supplierResponse->assertRedirect(route('suppliers.index'));
        $supplierResponse->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $product->category_id]);
        $this->assertDatabaseHas('suppliers', ['id' => $product->supplier_id]);
    }

    public function test_product_show_route_redirects_to_stock_page(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();

        $response = $this->actingAs($user)->get(route('products.show', $product));

        $response->assertRedirect(route('products.stock', $product));
    }

    private function createProduct(): Product
    {
        $category = Category::create(['nama' => 'Sembako']);
        $supplier = Supplier::create([
            'nama_supplier' => 'CV Maju Jaya',
            'alamat' => 'Jl. Sudirman 10',
            'no_telp' => '082233445566',
            'email' => 'majujaya@example.com',
        ]);

        return Product::create([
            'nama_barang' => 'Beras 5kg',
            'category_id' => $category->id,
            'harga_beli' => 65000,
            'harga_jual' => 70000,
            'supplier_id' => $supplier->id,
        ]);
    }
}
