<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;

class SembakoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Isi 5 supplier
        $suppliers = [
            [
                'nama_supplier' => 'PT Indofood Sukses Makmur',
                'alamat' => 'Jakarta',
                'no_telp' => '021-57958888',
                'email' => 'info@indofood.co.id',
            ],
            [
                'nama_supplier' => 'PT Wings Food',
                'alamat' => 'Surabaya',
                'no_telp' => '031-5668888',
                'email' => 'contact@wingscorp.com',
            ],
            [
                'nama_supplier' => 'PT Unilever Indonesia',
                'alamat' => 'Tangerang',
                'no_telp' => '021-5268888',
                'email' => 'consumer.care@unilever.com',
            ],
            [
                'nama_supplier' => 'PT Santos Jaya Abadi',
                'alamat' => 'Surabaya',
                'no_telp' => '031-5671234',
                'email' => 'info@santosjayaabadi.co.id',
            ],
            [
                'nama_supplier' => 'UD Sumber Rezeki (Lokal)',
                'alamat' => 'Pasar Induk Kramat Jati, Jakarta',
                'no_telp' => '0812-3456-7890',
                'email' => null,
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }

        // 2. Isi 9 produk sembako realistis
        Product::create([
            'nama_barang' => 'Beras Medium 5 kg',
            'kategori' => 'Beras',
            'harga_beli' => 65000,
            'harga_jual' => 75000,
            'stok' => 200,
            'supplier_id' => 1, // Indofood
        ]);

        Product::create([
            'nama_barang' => 'Minyak Goreng Sawit 1 L',
            'kategori' => 'Minyak',
            'harga_beli' => 14000,
            'harga_jual' => 18000,
            'stok' => 150,
            'supplier_id' => 2, // Wings
        ]);

        Product::create([
            'nama_barang' => 'Gula Pasir 1 kg',
            'kategori' => 'Gula',
            'harga_beli' => 15000,
            'harga_jual' => 18500,
            'stok' => 300,
            'supplier_id' => 1, // Indofood
        ]);

        Product::create([
            'nama_barang' => 'Telur Ayam Kampung 1 kg',
            'kategori' => 'Telur & Protein',
            'harga_beli' => 28000,
            'harga_jual' => 35000,
            'stok' => 100,
            'supplier_id' => 5, // Lokal
        ]);

        Product::create([
            'nama_barang' => 'Mie Instan Indomie Goreng',
            'kategori' => 'Makanan Instan',
            'harga_beli' => 2800,
            'harga_jual' => 3500,
            'stok' => 500,
            'supplier_id' => 1, // Indofood
        ]);

        Product::create([
            'nama_barang' => 'Sabun Mandi Lifebuoy',
            'kategori' => 'Sabun & Kebersihan',
            'harga_beli' => 4500,
            'harga_jual' => 6000,
            'stok' => 200,
            'supplier_id' => 3, // Unilever
        ]);

        Product::create([
            'nama_barang' => 'Deterjen Rinso 770 gr',
            'kategori' => 'Deterjen & Cuci',
            'harga_beli' => 18000,
            'harga_jual' => 22000,
            'stok' => 120,
            'supplier_id' => 3, // Unilever
        ]);

        Product::create([
            'nama_barang' => 'Susu Kental Manis Frisian Flag 370 gr',
            'kategori' => 'Susu & Minuman',
            'harga_beli' => 12000,
            'harga_jual' => 15000,
            'stok' => 180,
            'supplier_id' => 2, // Wings
        ]);

        Product::create([
            'nama_barang' => 'Kopi Sachet Kapal Api isi 20',
            'kategori' => 'Kopi & Teh',
            'harga_beli' => 22000,
            'harga_jual' => 28000,
            'stok' => 80,
            'supplier_id' => 4, // Santos Jaya Abadi
        ]);
    }
}