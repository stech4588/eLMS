<?php
namespace App\Services;

use App\Models\Watchlist;

class WatchlistService
{
    public function getAll() {
        return Watchlist::all();
    }

    public function getById(int $id) {
        return Watchlist::findOrFail($id);
    }

    public function create(array $data) {
        return Watchlist::create($data);
    }

    public function update(int $id, array $data) {
        $watchlist = Watchlist::findOrFail($id);
        $watchlist->update($data);
        return $watchlist;
    }

    public function delete(int $id): bool {
        return Watchlist::findOrFail($id)->delete();
    }
}
