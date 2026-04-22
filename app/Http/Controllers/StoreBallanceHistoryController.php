<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBallanceHistoryResource;
use App\Interfaces\StoreBallanceHistoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StoreBallanceHistoryController extends Controller implements HasMiddleware
{
    private StoreBallanceHistoryRepositoryInterface $storeBallanceHistoryRepository;

    public function __construct(StoreBallanceHistoryRepositoryInterface $storeBallanceHistoryRepository) {
        $this->storeBallanceHistoryRepository = $storeBallanceHistoryRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['store-ballance-history-list']), only: ['index', 'getAllPaginated', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBallanceHistories = $this->storeBallanceHistoryRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'History data e-wallet store has been successfully retrieved', StoreBallanceHistoryResource::collection($storeBallanceHistories), 200);
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
            $storeBallanceHistories = $this->storeBallanceHistoryRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
            );
            return ResponseHelper::jsonResponse(true, 'History data e-wallet store has been successfully retrieved', PaginateResource::make($storeBallanceHistories, StoreBallanceHistoryResource::class), 200);
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
            $storeBallanceHistory = $this->storeBallanceHistoryRepository->getById($id);
            if(!$storeBallanceHistory) {
                return ResponseHelper::jsonResponse(true, 'History data e-wallet store not found', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'History data e-wallet store has been found', new StoreBallanceHistoryResource($storeBallanceHistory), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
