<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();

        $stats = [
            'total'     => $items->count(),
            'total_qty' => $items->sum('qty'),
            'ok'        => $items->filter(fn($i) => $i->stock_status === 'ok')->count(),
            'warn'      => $items->filter(fn($i) => $i->stock_status === 'warn')->count(),
            'low'       => $items->filter(fn($i) => $i->stock_status === 'low')->count(),
        ];

        return view('inventory.index', compact('items', 'stats'));
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

        $item->update($data);

        return response()->json([
            'success' => true,
            'message' => "\"$item->name\" berhasil diperbarui!",
            'item'    => $item->fresh()->append(['stock_status', 'stock_label']),
        ]);
    }

    public function destroy(Item $item): JsonResponse
    {
        $name = $item->name;
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
