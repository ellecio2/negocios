<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopAdditionalCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_cargo',
        'monto',
        'estado_entrega',
        'estado_pago',
        'proposal_id',
    ];

    public function workshopServiceProposal()
    {
        return $this->belongsTo(WorkshopServiceProposal::class, 'proposal_id');
    }
}