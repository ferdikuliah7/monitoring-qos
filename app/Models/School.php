<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'ip_target',
        'bandwidth',
        'status',
    ];

    protected $casts = [
        'bandwidth' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function qosMeasurements()
    {
        return $this->hasMany(QosMeasurement::class);
    }

    public function latestQosMeasurement()
    {
        return $this->hasOne(QosMeasurement::class)
            ->latestOfMany('measured_at');
    }
}
