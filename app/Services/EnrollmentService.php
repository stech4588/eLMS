<?php
namespace App\Services;

use App\Models\Enrollment;

class EnrollmentService
{
    public function getAll() {
        return Enrollment::all();
    }

    public function getById(int $id) {
        return Enrollment::findOrFail($id);
    }

    public function create(array $data) {
        return Enrollment::create($data);
    }

    public function update(int $id, array $data) {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->update($data);
        return $enrollment;
    }

    public function delete(int $id): bool {
        return Enrollment::findOrFail($id)->delete();
    }
}
