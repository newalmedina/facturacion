<?php
// app/Models/CmsContent.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsContent extends Model
{
    use HasFactory;

    protected $table = 'cms_contents';

    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(CmsContentImage::class)->orderBy('sort');;
    }
    public function activeImages()
    {
        return $this->hasMany(CmsContentImage::class)
            ->where('active', 1)->orderBy('sort');;
    }
    public function lastImage()
    {
        return $this->hasOne(CmsContentImage::class)
            ->latestOfMany('id'); // o cualquier otra columna
    }
    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }
}
