<?php

// app/Models/CmsContentImage.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsContentImage extends Model
{
    use HasFactory;

    protected $table = 'cms_content_images';

    protected $guarded = [];

    // protected $fillable = [
    //     'cms_content_id',
    //     'image_path',
    //     'title',
    //     'alt_text',
    // ];

    public function content()
    {
        return $this->belongsTo(CmsContent::class, 'cms_content_id');
    }
}
