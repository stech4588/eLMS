<?php
namespace App\Services;

use App\Models\Review;

class ReviewService
{
    public function getAll() {
        return Review::all();
    }

    public function getById(int $id) {
        return Review::findOrFail($id);
    }

    public function create(array $data) {
        return Review::create($data);
    }

    public function update(int $id, array $data) {
        $review = Review::findOrFail($id);
        $review->update($data);
        return $review;
    }

    public function delete(int $id): bool {
        return Review::findOrFail($id)->delete();
    }
}
