<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappOpenedConversation extends Model {

    protected $fillable = [
        'type',
        'process',
        'expiration_date',
        'combined_order_id'
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'users_has_conversations', 'conversation_id', 'user_id')
            ->withPivot(['workshop_proposal_id', 'workshop_id', 'process']);
    }

    public function combinedOrder(){
        return $this->belongsTo(CombinedOrder::class);
    }
}
