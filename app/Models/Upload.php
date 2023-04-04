<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'path', 'product_id'];



    public function setFilenameAttribute($value)
    {
        $filename = uniqid() . '_' . time() . '.' . $value->getClientOriginalExtension();
        $this->attributes['filename'] = $filename;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
