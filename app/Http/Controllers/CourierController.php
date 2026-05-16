<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CourierController extends Controller
{
    /**
     * Controller Index
     * Fitur: Pagination, Default Sort Nama, Override Sort Tanggal (?sort=date),
     * Search Nama (?search=budi+agung), Filter Banyak Level (?level=2,3)
     */
    public function index(Request $request)
    {
        $query = Courier::query();

        // Fitur Search Nama: ?search=budi+agung -> mencocokkan "Budiono Hadi Agung"
        if ($request->has('search')) {
            $searchTerms = explode(' ', $request->query('search'));
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where('name', 'LIKE', '%' . $term . '%');
                }
            });
        }

        // Fitur Filter Level: ?level=2,3
        if ($request->has('level')) {
            $levels = explode(',', $request->query('level'));
            $query->whereIn('level', $levels);
        }

        // Fitur Sorting: Default nama (asc), Opsi frontend: ?sort=date -> berdasarkan tanggal didaftarkan
        $sortBy = 'name';
        $sortOrder = 'asc';

        if ($request->query('sort') === 'date') {
            $sortBy = 'created_at';
            $sortOrder = 'desc';
        }

        // Fitur Pagination: Membagi hasil data per halaman
        $couriers = $query->orderBy($sortBy, $sortOrder)->paginate(10);

        return response()->json($couriers, 200);
    }

    /**
     * Controller Store
     * Fitur: Validasi Lengkap, Umur >= 15 tahun, NIK 16 digit, Level 1-5, myUUID -> created_by, Anti SQL Injection
     */
    public function store(Request $request)
    {
        $maxBirthDate = Carbon::now()->subYears(15)->format('Y-m-d');

        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'nik' => 'required|digits:16|unique:couriers,nik',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'dob' => 'required|date|before_or_equal:' . $maxBirthDate,
                'status' => 'required|string|max:50',
                'level' => 'required|integer|between:1,5',
                'myUUID' => 'required|uuid'
            ]);

            $courier = Courier::create([
                'name' => $validated['name'],
                'nik' => $validated['nik'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'dob' => $validated['dob'],
                'status' => $validated['status'],
                'level' => $validated['level'],
                'created_by' => $validated['myUUID']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kurir berhasil disimpan ke database.',
                'data' => $courier
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Controller Show
     * Fitur: Mengembalikan semua data kurir berdasarkan UUID
     */
    public function show($uuid)
    {
        try {
            $courier = Courier::where('uuid', $uuid)->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $courier
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kurir tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Controller Update
     * Fitur: Validasi Lengkap, Unik NIK kecuali milik data ini sendiri, myUUID -> updated_by
     */
    public function update(Request $request, $uuid)
    {
        try {
            $courier = Courier::where('uuid', $uuid)->firstOrFail();
            $maxBirthDate = Carbon::now()->subYears(15)->format('Y-m-d');

            $validated = $request->validate([
                'name' => 'required|string',
                'nik' => 'required|digits:16|unique:couriers,nik,' . $courier->uuid . ',uuid',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'dob' => 'required|date|before_or_equal:' . $maxBirthDate,
                'status' => 'required|string|max:50',
                'level' => 'required|integer|between:1,5',
                'myUUID' => 'required|uuid'
            ]);

            $courier->update([
                'name' => $validated['name'],
                'nik' => $validated['nik'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'dob' => $validated['dob'],
                'status' => $validated['status'],
                'level' => $validated['level'],
                'updated_by' => $validated['myUUID']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kurir berhasil diperbarui di database.',
                'data' => $courier
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kurir tidak ditemukan.'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ], 500);
        } 
    }

    /**
     * Controller Destroy
     * Fitur: Menghapus data dari database
     */
    public function destroy($uuid)
    {
        try {   
            $courier = Courier::where('uuid', $uuid)->firstOrFail();
            $courier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kurir telah hilang dari database.'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kurir tidak ditemukan.'
            ], 404);
        }
    }
}