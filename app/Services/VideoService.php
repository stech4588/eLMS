<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class VideoService
{
    public function getAll(): Collection
    {
        return Video::all();
    }

    public function getById(int $id): Video
    {
        return Video::findOrFail($id);
    }

    public function create(array $data): Video
    {
        return Video::create($data);
    }

    public function update(int $id, array $data): Video
    {
        $video = Video::findOrFail($id);
        $video->update($data);
        return $video;
    }

    public function delete(int $id): bool
    {
        $video = Video::findOrFail($id);
        return $video->delete();
    }
}
