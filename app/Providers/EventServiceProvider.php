<?php

namespace App\Providers;

use App\Events\AcceptServiceWorkshopProposalEvent;
use App\Events\ServiceRequestEvent;
use App\Events\ServiceWorkshopProposalEvent;
use App\Events\workshopAdditionalEvent;
use App\Listeners\AcceptServiceWorkshopProposalListener;
use App\Listeners\ServiceRequestListener;
use App\Listeners\ServiceWorkshopProposalListener;
use App\Listeners\workshopAdditionalListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
    ServiceRequestEvent::class=> [
      ServiceRequestListener::class,
    ],
    ServiceWorkshopProposalEvent::class=> [
      ServiceWorkshopProposalListener::class,
    ],

    AcceptServiceWorkshopProposalEvent::class=> [
      AcceptServiceWorkshopProposalListener::class,
    ],

    workshopAdditionalEvent::class=> [
      workshopAdditionalListener::class,
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    //
  }
}
