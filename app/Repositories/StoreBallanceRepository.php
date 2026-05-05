<?php

namespace App\Repositories;

use App\Interfaces\StoreBallanceRepositoryInterface;
use App\Models\StoreBallance;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreBallanceRepository implements StoreBallanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = StoreBallance::where(function ($query) use ($search) {
            if($search) {
                $query->search($search);
            }
        })->with(['storeBallanceHistories']);

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
        $query = StoreBallance::where('id', $id)->with(['storeBallanceHistories']);
        return $query->first();
    }

    public function getByStore()
    {
        $user = Auth::user();
        $query = StoreBallance::where('store_id', $user->store->id);
        return $query->first();
    }

    public function creadit(string $id, string $amount)
    {
        DB::beginTransaction();
        try {
            $storeBallance = StoreBallance::find($id);
            $storeBallance->balance = bcadd($storeBallance->balance, $amount, 2);
            $storeBallance->save();
            DB::commit();
            return $storeBallance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function debit(string $id, string $amount)
    {
        DB::beginTransaction();
        try {
            $storeBallance = StoreBallance::find($id);
            if(bccomp($storeBallance->balance, $amount, 2) < 0) {
                throw new Exception("Insufficient balance");
            }
            $storeBallance->balance = bcsub($storeBallance->balance, $amount, 2);
            $storeBallance->save();
            DB::commit();
            return $storeBallance;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}