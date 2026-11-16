<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->get();

        return view('buku.index', compact('bukus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'  => 'required|integer',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
        ]);

        $buku = Buku::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Buku berhasil ditambahkan',
                'data'    => $buku
            ], 201);
        }

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return response()->json(['data' => $buku]);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kategori_id'  => 'required|integer',
            'judul'        => 'required|string|max:255',
            'penulis'      => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
        ]);

        $buku->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Buku berhasil diperbarui',
                'data'    => $buku
            ], 200);
        }

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function detail($id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return view('buku.detail', [
                'buku' => null
            ]);
        }

        return view('buku.detail', compact('buku'));
    }

    public function cari(Request $request)
    {
        $judul = $request->query('judul');
        $buku = DB::select("SELECT * FROM bukus WHERE judul = ?", [$judul]);

        return response()->json($buku);
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Buku berhasil dihapus!'
            ], 200);
        }

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}