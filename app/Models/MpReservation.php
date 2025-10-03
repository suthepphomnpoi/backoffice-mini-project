<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpReservation extends Model
{
    protected $connection = 'oracle';
    protected $table = 'mp_reservations';
    protected $primaryKey = 'reservation_id';
    public $timestamps = true;

    protected $fillable = [
        'trip_id', 'user_id', 'seats_reserved', 'status', 'qr_code', 'notes', 'origin_place_id', 'destination_place_id'
    ];

    protected $casts = [
        'trip_id' => 'integer',
        'user_id' => 'integer',
        'seats_reserved' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trip()
    {
        return $this->belongsTo(MpTrip::class, 'trip_id', 'trip_id');
    }

    public function user()
    {
        return $this->belongsTo(MpUser::class, 'user_id', 'user_id');
    }
}
