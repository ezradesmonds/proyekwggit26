<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        
        // Ambil 30 log terbaru untuk ditampilkan di timeline bawah
        $logs = ItemLog::latest()->take(30)->get();

        $stats = [
            'total'     => $items->count(),
            'total_qty' => $items->sum('qty'),
            'ok'        => $items->filter(fn($i) => $i->stock_status === 'ok')->count(),
            'warn'      => $items->filter(fn($i) => $i->stock_status === 'warn')->count(),
            'low'       => $items->filter(fn($i) => $i->stock_status === 'low')->count(),
        ];

        return view('inventory.index', compact('items', 'stats', 'logs'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'qty'       => 'required|integer|min:0',
            'category'  => 'required|string|max:100',
            'min_stock' => 'required|integer|min:0',
        ]);

        $item = Item::create($data);

        // Catat Log Tambah Barang
        ItemLog::create([
            'item_id'   => $item->id,
            'item_name' => $item->name,
            'action'    => 'Barang Baru Ditambahkan',
            'notes'     => "Stok awal: {$item->qty} unit"
        ]);

        return response()->json([
            'success' => true,
            'message' => "\"$item->name\" berhasil ditambahkan!",
            'item'    => $item->append(['stock_status', 'stock_label']),
        ]);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'qty'       => 'required|integer|min:0',
            'category'  => 'required|string|max:100',
            'min_stock' => 'required|integer|min:0',
        ]);

        // Simpan qty lama untuk dicek apakah ada perubahan
        $oldQty = $item->qty;
        
        $item->update($data);

        // Tentukan teks aksi (kalau cuma ganti nama/kategori bedakan sama ganti stok)
        $actionText = 'Data Diperbarui';
        if ($oldQty != $item->qty) {
            $actionText = "Update Stok ({$oldQty} ➔ {$item->qty})";
        }

        // Catat Log Edit Barang beserta Notes yang diketik user
        ItemLog::create([
            'item_id'   => $item->id,
            'item_name' => $item->name,
            'action'    => $actionText,
            'notes'     => $request->input('notes') ?: 'Tanpa catatan'
        ]);

        return response()->json([
            'success' => true,
            'message' => "\"$item->name\" berhasil diperbarui!",
            'item'    => $item->fresh()->append(['stock_status', 'stock_label']),
        ]);
    }

    public function destroy(Item $item): JsonResponse
    {
        $name = $item->name;
        
        // Catat Log Hapus Barang (item_id dikosongi karena barisnya akan dihapus)
        ItemLog::create([
            'item_id'   => null,
            'item_name' => $name,
            'action'    => 'Barang Dihapus',
            'notes'     => 'Data barang telah dihapus permanen dari sistem'
        ]);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => "\"$name\" berhasil dihapus.",
        ]);
    }

    public function stats(): JsonResponse
    {
        $items = Item::all();

        return response()->json([
            'total'     => $items->count(),
            'total_qty' => $items->sum('qty'),
            'ok'        => $items->filter(fn($i) => $i->stock_status === 'ok')->count(),
            'warn'      => $items->filter(fn($i) => $i->stock_status === 'warn')->count(),
            'low'       => $items->filter(fn($i) => $i->stock_status === 'low')->count(),
        ]);
    }
}