<?php

namespace App\Console\Commands;

use App\Http\Controllers\Mensajeria\WorkShopController;
use Illuminate\Console\Command;

class WorkshopsStartCommand extends Command
{
    protected $signature = 'workshops:start';

    protected $description = 'Start every day a workshops';

    public function handle(): void {
        WorkShopController::startWorkshops();
    }
}
