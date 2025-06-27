<?php

namespace App\Classes;

class Language {
    public string $code;

    public function __construct(string $code){
        $this->code = $code;
    }
}
