<?php

namespace App\Classes;

class Parameter {
    public string $type;
    public string $text;

    public function __construct(string $type, string $content){
        $this->type = $type;

        if($this->type == 'text'){
            $this->text = $content;
        }
    }
}
