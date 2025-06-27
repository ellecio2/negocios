<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkShopProposal extends Model {
    protected $table = 'workshop_proposals';

    protected $fillable = [
        'note',
        'price',
        'selected_day',
        'combined_order_id'
    ];

    protected $attributes = [
        'price' => 0.0
    ];

    public function combinedOrder(){
        return $this->belongsTo(CombinedOrder::class);
    }

    public function openedConversation(){
        return $this->hasOne(WhatsappOpenedConversation::class, 'workshop_proposal_id', 'id');
    }
}
