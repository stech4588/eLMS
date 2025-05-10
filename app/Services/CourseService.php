<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Collection;

class CourseService
{
    public function getAll(): Collection
    {
        return Course::all();
    }

    public function getById(int $id): Course
    {
        return Course::findOrFail($id);
    }

    public function create(array $data): Course
    {
        return Course::create($data);
    }

    public function update(int $id, array $data): Course
    {
        $course = Course::findOrFail($id);
        $course->update($data);
        return $course;
    }

    public function delete(int $id): bool
    {
        $course = Course::findOrFail($id);
        return $course->delete();
    }
}
