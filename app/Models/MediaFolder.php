<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaFolder extends Model
{
    use SoftDeletes;

    protected $table = 'media_folders';
    protected $fillable = ['name', 'type', 'reference_id', 'created_by'];

    public function media()
    {
        return $this->hasMany(Media::class, 'folder_id');
    }

    public function coverImage()
    {
        return $this->hasOne(Media::class, 'folder_id')->latest();
    }

    public static function getList()
    {
        return self::withCount('media')->orderByRaw('reference_id IS NULL, reference_id DESC')->get();
    }

    public function getEditUrlAttribute()
    {
        if (empty($this->reference_id)) {
            return route('admin.media.folders');
        }

        if ($this->type === 'movie') {
            return route('admin.movies.edit', $this->reference_id);
        }

        if ($this->type === 'webseries') {
            return route('admin.webseries.edit', $this->reference_id);
        }

        return route('admin.media.folders');
    }
}