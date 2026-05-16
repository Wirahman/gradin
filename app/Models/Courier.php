<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Courier extends Model
{
    use HasUuids;

    protected $table = 'couriers';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    // Laravel secara default menggunakan created_at dan updated_at
    public $timestamps = true;

    protected $fillable = [
        'name',
        'nik',
        'address',
        'phone',
        'dob',
        'status',
        'level',
        'created_by',
        'updated_by'
    ];

    /**
     * Memperbaiki binding agar Laravel otomatis mencari data berdasarkan UUID, bukan ID.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}