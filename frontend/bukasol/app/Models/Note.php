<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [ 
        'student_id',
        'time_stamp',
        'category',
        'activity',
        'activity_detail',
        'parent_question',
        'teacher_answer',
        'teacher_sign',
    ];

    protected function casts () : array
    {
        return [ 
            'id'              => 'integer',
            'student_id'      => 'integer',
            'time_stamp'      => 'date',
            'category'        => 'string',
            'activity'        => 'string',
            'activity_detail' => 'string',
            'parent_question' => 'string',
            'teacher_answer'  => 'string',
            'teacher_sign'    => 'boolean',
        ];
    }

    public function student ()
    {
        return $this->belongsTo ( Student::class);
    }
}
