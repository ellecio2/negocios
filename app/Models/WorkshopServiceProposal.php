<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopServiceProposal extends Model
{
    use HasFactory;

    protected $table = 'workshop_service_proposals';

    protected $fillable = [
        'workshop_id',
        'user_id',
        'order_id',
        'nota',
        'installation_amount',
        'time_estimate',
        'date_time_inicial',
        'date_time_final',
        'current_acceptance_request_date',
        'client_accepts_mechanic',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function workshopAdditionalCharges()
    {
        return $this->hasMany(WorkshopAdditionalCharge::class, 'proposal_id');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id');
    }
    
    //esto funciono para lograr mostrar la direccion desde workshopServiceProposals que 
    //es el recorrido donde me dirijo a la relacion Workshop su user_id y 
    //comparo en addres con user_id para lograr mostrar la direccion del taller 
    public function address()
    {
        return $this->belongsTo(Address::class, 'user_id', 'user_id');
    }
}
