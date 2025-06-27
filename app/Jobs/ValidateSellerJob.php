<?php

namespace App\Jobs;

use App\Models\Seller;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mindee\Client;
use Mindee\Product\InternationalId\InternationalIdV2;

class ValidateSellerJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $seller;

    public function __construct(User $seller) {
        $this->seller = $seller;
    }

    public function handle(): void {
        $mindeeClient = new Client(config('app.mindee_api_key'));

        $upload = Upload::find($this->seller->cedula_id);
        $file_name = $upload->file_name;
        $filePath = public_path($file_name);

        // Load a file from disk
        $inputSource = $mindeeClient->sourceFromPath("$filePath");

        // Parse the file asynchronously
        $apiResponse = $mindeeClient->enqueueAndParse(InternationalIdV2::class, $inputSource);

        echo $apiResponse->document->inference->prediction->documentNumber->value;
    }
}
