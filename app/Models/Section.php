<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $table = 'sections';
    public $fillable = [
        'title',
        'details',
        'country',
        'city',
        'startDate',
        'isActive',
        'endDate',
        'isShown',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
