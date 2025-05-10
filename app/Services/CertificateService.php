<?php
namespace App\Services;

use App\Models\Certificate;

class CertificateService
{
    public function getAll() {
        return Certificate::all();
    }

    public function getById(int $id) {
        return Certificate::findOrFail($id);
    }

    public function create(array $data) {
        return Certificate::create($data);
    }

    public function update(int $id, array $data) {
        $certificate = Certificate::findOrFail($id);
        $certificate->update($data);
        return $certificate;
    }

    public function delete(int $id): bool {
        return Certificate::findOrFail($id)->delete();
    }
}
