<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $address
 * @property string $city
 * @property float $latitude
 * @property float $longitude
 * @property int $postal_code
 * @property string $phone
 */
class Address extends Model {
    protected $fillable = [
        'user_id',
        'country',
        'state',
        'address',
        'city',
        'postalCode',
        'postal_code',
        'country_id',
        'state_id',
        'city_id',
        'longitude',
        'latitude',
        'phone',
        'set_default'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function scopeDefault($query): void {
        $query->where('set_default', 1);
    }

    public function scopeOwnedByCurrentUser($query): void {
        $query->where('user_id', auth()->id());
    }
}
