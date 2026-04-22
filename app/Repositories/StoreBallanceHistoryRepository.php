<?php

namespace App\Repositories;

use App\Interfaces\StoreBallanceHistoryRepositoryInterface;
use App\Models\StoreBallanceHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBallanceHistoryRepository implements StoreBallanceHistoryRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = StoreBallanceHistory::where(function ($query) use ($search) {
            if($search) {
                $query->search($search);
            }
        });

        if($limit) {
            $query->take($limit);
        }

        if($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            null,
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = StoreBallanceHistory::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $storeBallanceHistory = new StoreBallanceHistory();
            $storeBallanceHistory->store_ballance_id = $data['store_ballance_id'];
            $storeBallanceHistory->type = $data['type'];
            $storeBallanceHistory->reference_id = $data['reference_id'];
            $storeBallanceHistory->reference_type = $data['reference_type'];
            $storeBallanceHistory->amount = $data['amount'];
            $storeBallanceHistory->remarks = $data['remarks'];
            $storeBallanceHistory->save();
            DB::commit();
            return $storeBallanceHistory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $storeBallanceHistory = StoreBallanceHistory::find($id);
            $storeBallanceHistory->type = $data['type'];
            $storeBallanceHistory->reference_id = $data['reference_id'];
            $storeBallanceHistory->reference_type = $data['reference_type'];
            $storeBallanceHistory->amount = $data['amount'];
            $storeBallanceHistory->remarks = $data['remarks'];
            $storeBallanceHistory->save();
            DB::commit();
            return $storeBallanceHistory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}