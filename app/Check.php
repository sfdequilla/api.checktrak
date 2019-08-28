<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Check extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function payee()
    {
        return $this->belongsTo(Payee::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function transmittals()
    {
        return $this->belongsToMany(Transmittal::class);
    }

    public function branch()
    {
        if ( ! $this->transmittals->count() || ( $this->transmittals->reverse()->first()->returned && $this->received ) ) {
            return Branch::first();
        } else {
            return $this->transmittals->reverse()->first()->branch;
        }
    }
}
