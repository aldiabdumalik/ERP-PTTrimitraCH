<?php

namespace App\Models\Dbtbs\DB_parts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Revision extends Model
{
    use Notifiable;

    protected $connection = 'db_tbs';
    protected $table = 'dbparts_revision_tbl';
    protected $fillable = [
        'id',
        'revision_number',
        'id_type',
        'note',
        'posted_by',
        'posted_at',
        'approved_by',
        'approved_at',
    ];   
    protected $hidden = [];
   
    public $timestamps = false;

    public function scopeType($query, $type)
    {
        return $query->where('id_type', $type);
    }

    public function scopeLastNumber($query)
    {
        return $query->orderBy('revision_number', 'DESC');
    }

    public function project()
    {
        return $this->belongsTo(Projects::class, 'id_type', 'id');
    }
}
