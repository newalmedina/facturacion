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
        return $this->hasMany(CmsContentImage::class);
    }
}
