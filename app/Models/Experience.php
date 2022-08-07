<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Section
{
    use HasFactory;
    protected $table = 'experiences';
    public $fillable = [
        'companyName',
        'section_id',
    ];

    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id');
    }

}
