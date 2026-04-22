<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreResource;
use App\Interfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StoreController extends Controller implements HasMiddleware
{
    private StoreRepositoryInterface $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository) {
        $this->storeRepository = $storeRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['store-list|store-create|store-edit|store-delete']), only: ['index', 'getAllPaginated', 'show', 'updateVerifiedStatus']),
            new Middleware(PermissionMiddleware::using(['store-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['store-edit']), only: ['update', 'updateVerifiedStatus']),
            new Middleware(PermissionMiddleware::using(['store-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $stores = $this->storeRepository->getAll(
                $request->search,
                $request->is_verified,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data store has been successfully retrieved', StoreResource::collection($stores), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $stores = $this->storeRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['is_verified'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data store has been successfully retrieved', PaginateResource::make($stores, StoreResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $store = $this->storeRepository->create($request);
            return ResponseHelper::jsonResponse(true, 'Data store has been created', new StoreResource($store), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $store = $this->storeRepository->getById($id);
            if(!$store) {
                return ResponseHelper::jsonResponse(true, 'Data store not found', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data store has been found', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function updateVerifiedStatus(string $id)
    {
        try {
            $store = $this->storeRepository->getById($id);
            if(!$store) {
                return ResponseHelper::jsonResponse(true, 'Data store not found', null, 404);
            }
            $store = $this->storeRepository->updateVerifiedStatus($id, true);
            return ResponseHelper::jsonResponse(true, 'Data store has been verification', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $store = $this->storeRepository->getById($id);
            if(!$store) {
                return ResponseHelper::jsonResponse(true, 'Data store not found', null, 404);
            }
            $store =  $this->storeRepository->update($id, $request);
            return ResponseHelper::jsonResponse(true, 'Data store has been updated', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $store = $this->storeRepository->getById($id);
            if(!$store) {
                return ResponseHelper::jsonResponse(true, 'Data store not found', null, 404);
            }
            $store =  $this->storeRepository->delete($id);
            return ResponseHelper::jsonResponse(true, 'Data store has been deleted', new StoreResource($store), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
