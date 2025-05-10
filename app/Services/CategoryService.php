<?php
namespace App\Services;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function getAll(): Collection
    {
        return CourseCategory::all();
    }

    public function getById(int $id): CourseCategory
    {
        return CourseCategory::findOrFail($id);
    }

    public function create(array $data): CourseCategory
    {
        return CourseCategory::create($data);
    }

    public function update(int $id, array $data): CourseCategory
    {
        $category = CourseCategory::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete(int $id): bool
    {
        $category = CourseCategory::findOrFail($id);
        return $category->delete();
    }
}
