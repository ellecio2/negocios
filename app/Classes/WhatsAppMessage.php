<?php

namespace App\Classes;
class WhatsAppMessage {
    public string $messaging_product;
    public string $to;
    public string $type;
    public mixed $template;

    public function __construct(mixed $fillable){
        $this->messaging_product = $fillable['messaging_product'];
        $this->to = $fillable['to'];
        $this->type = $fillable['type'];
        $this->template = new Template($fillable['template']['language'], $fillable['template']['components'], $fillable['template']['name']);
    }

    public function toJson(): string {
        return json_encode($this);
    }


}
