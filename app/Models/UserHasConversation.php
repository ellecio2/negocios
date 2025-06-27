<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHasConversation extends Model{

    protected $primaryKey = 'id';

    protected $table = 'users_has_conversations';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'workshop_id',
        'workshop_proposal_id',
        'process'
    ];

    public $timestamps = false;

    public function conversation(){
        return $this->hasOne(WhatsappOpenedConversation::class, 'id', 'conversation_id');
    }

    public function workshop(){
        return $this->belongsTo(Workshop::class, 'workshop_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function proposal(){
        return $this->belongsTo(WorkShopProposal::class, 'workshop_proposal_id', 'id');
    }
}
