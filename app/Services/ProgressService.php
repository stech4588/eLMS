<?php
namespace App\Services;

use App\Models\Progress;

class ProgressService
{
    public function getAll() {
        return Progress::all();
    }

    public function getById(int $id) {
        return Progress::findOrFail($id);
    }

    public function create(array $data) {
        return Progress::create($data);
    }

    public function update(int $id, array $data) {
        $progress = Progress::findOrFail($id);
        $progress->update($data);
        return $progress;
    }

    public function delete(int $id): bool {
        return Progress::findOrFail($id)->delete();
    }
}
