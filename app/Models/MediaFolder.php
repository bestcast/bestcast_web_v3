<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFolder extends Model
{
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
        return self::withCount('media')->orderBy('reference_id', 'desc')->get();
    }
}