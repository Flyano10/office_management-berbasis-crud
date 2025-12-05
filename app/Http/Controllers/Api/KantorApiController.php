<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kantor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KantorApiController extends ApiController
{
    /**
     * Display a listing of kantor with pagination and filtering
     */
    public function index(Request $request): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.index', 100)) {
            return $this->rateLimitExceededResponse();
        }

        try {
            $query = Kantor::with(['jenisKantor', 'kota']);

            // Terapkan filter
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('nama_kantor', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('kode_kantor', 'like', "%{$search}%");
                });
            }

            if ($request->has('jenis_kantor_id')) {
                $query->where('jenis_kantor_id', $request->get('jenis_kantor_id'));
            }

            if ($request->has('kota_id')) {
                $query->where('kota_id', $request->get('kota_id'));
            }

            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $kantor = $query->paginate($perPage);

            $this->logApiRequest($request, 'kantor.index', ['filters' => $request->all()]);

            return $this->successResponse([
                'kantor' => $kantor->items(),
                'pagination' => $this->getPaginationData($kantor)
            ], 'Kantor data retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve kantor data: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created kantor
     */
    public function store(Request $request): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.store', 20)) {
            return $this->rateLimitExceededResponse();
        }

        $validator = Validator::make($request->all(), [
            'nama_kantor' => 'required|string|max:255',
            'kode_kantor' => 'required|string|max:50|unique:kantor',
            'alamat' => 'required|string',
            'jenis_kantor_id' => 'required|exists:jenis_kantor,id',
            'kota_id' => 'required|exists:kota,id',
            'status' => 'required|in:aktif,non_aktif',
            'status_kepemilikan' => 'required|in:milik,sewa',
            'luas_tanah' => 'nullable|numeric|min:0',
            'luas_bangunan' => 'nullable|numeric|min:0',
            'daya_listrik_va' => 'nullable|integer|min:0',
            'kapasitas_genset_kva' => 'nullable|integer|min:0',
            'jumlah_sumur' => 'nullable|integer|min:0',
            'jumlah_septictank' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $kantor = Kantor::create($request->all());

            $this->logApiRequest($request, 'kantor.store', $kantor->toArray());

            DB::commit();

            return $this->successResponse($kantor->load(['jenisKantor', 'kota']), 'Kantor created successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create kantor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified kantor
     */
    public function show(Request $request, $id): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.show', 100)) {
            return $this->rateLimitExceededResponse();
        }

        try {
            $kantor = Kantor::with(['jenisKantor', 'kota', 'gedung'])->findOrFail($id);

            $this->logApiRequest($request, 'kantor.show', ['id' => $id]);

            return $this->successResponse($kantor, 'Kantor data retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Kantor not found', 404);
        }
    }

    /**
     * Update the specified kantor
     */
    public function update(Request $request, $id): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.update', 20)) {
            return $this->rateLimitExceededResponse();
        }

        $validator = Validator::make($request->all(), [
            'nama_kantor' => 'sometimes|required|string|max:255',
            'kode_kantor' => 'sometimes|required|string|max:50|unique:kantor,kode_kantor,' . $id,
            'alamat' => 'sometimes|required|string',
            'jenis_kantor_id' => 'sometimes|required|exists:jenis_kantor,id',
            'kota_id' => 'sometimes|required|exists:kota,id',
            'status' => 'sometimes|required|in:aktif,non_aktif',
            'status_kepemilikan' => 'sometimes|required|in:milik,sewa',
            'luas_tanah' => 'nullable|numeric|min:0',
            'luas_bangunan' => 'nullable|numeric|min:0',
            'daya_listrik_va' => 'nullable|integer|min:0',
            'kapasitas_genset_kva' => 'nullable|integer|min:0',
            'jumlah_sumur' => 'nullable|integer|min:0',
            'jumlah_septictank' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        try {
            DB::beginTransaction();

            $kantor = Kantor::findOrFail($id);
            $kantor->update($request->all());

            $this->logApiRequest($request, 'kantor.update', array_merge(['id' => $id], $request->all()));

            DB::commit();

            return $this->successResponse($kantor->load(['jenisKantor', 'kota']), 'Kantor updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update kantor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified kantor
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.destroy', 10)) {
            return $this->rateLimitExceededResponse();
        }

        try {
            DB::beginTransaction();

            $kantor = Kantor::findOrFail($id);
            $kantor->delete();

            $this->logApiRequest($request, 'kantor.destroy', ['id' => $id]);

            DB::commit();

            return $this->successResponse(null, 'Kantor deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete kantor: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get kantor statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        // Cek rate limiting
        if (!$this->checkRateLimit($request, 'kantor.statistics', 30)) {
            return $this->rateLimitExceededResponse();
        }

        try {
            $stats = [
                'total_kantor' => Kantor::count(),
                'aktif_kantor' => Kantor::where('status', 'aktif')->count(),
                'non_aktif_kantor' => Kantor::where('status', 'non_aktif')->count(),
                'milik_kantor' => Kantor::where('status_kepemilikan', 'milik')->count(),
                'sewa_kantor' => Kantor::where('status_kepemilikan', 'sewa')->count(),
                'by_jenis' => Kantor::with('jenisKantor')
                    ->selectRaw('jenis_kantor_id, count(*) as total')
                    ->groupBy('jenis_kantor_id')
                    ->get(),
                'by_kota' => Kantor::with('kota')
                    ->selectRaw('kota_id, count(*) as total')
                    ->groupBy('kota_id')
                    ->get()
            ];

            $this->logApiRequest($request, 'kantor.statistics');

            return $this->successResponse($stats, 'Kantor statistics retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve kantor statistics: ' . $e->getMessage(), 500);
        }
    }
}
