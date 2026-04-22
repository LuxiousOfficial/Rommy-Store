<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBallanceResource;
use App\Interfaces\StoreBallanceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StoreBallanceController extends Controller implements HasMiddleware
{
    private StoreBallanceRepositoryInterface $storeBallanceRepository;

    public function __construct(StoreBallanceRepositoryInterface $storeBallanceRepository) {
        $this->storeBallanceRepository = $storeBallanceRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['store-ballance-list']), only: ['index', 'getAllPaginated', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBallances = $this->storeBallanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data e-wallet has been successfully retrieved', StoreBallanceResource::collection($storeBallances), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $storeBallances = $this->storeBallanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'Data e-wallet has been successfully retrieved', PaginateResource::make($storeBallances, StoreBallanceResource::class), 200);
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
            $storeBalance = $this->storeBallanceRepository->getById($id);
            if(!$storeBalance) {
                return ResponseHelper::jsonResponse(true, 'Data e-wallet not found', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data e-wallet has been found', new StoreBallanceResource($storeBalance), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
