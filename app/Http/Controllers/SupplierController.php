<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|unique:suppliers,nama_supplier',
            'alamat' => 'nullable',
            'no_telp' => 'nullable',
            'email' => 'nullable|email',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required|unique:suppliers,nama_supplier,' . $supplier->id,
            'alamat' => 'nullable',
            'no_telp' => 'nullable',
            'email' => 'nullable|email',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak bisa dihapus karena masih dipakai produk');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus');
    }
}