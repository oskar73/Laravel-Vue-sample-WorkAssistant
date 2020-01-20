<?php

namespace App\Jobs;

use App\Models\GraphicDesign\UserDesign;
use App\Services\GenerateDesignPackageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDesignPackageToClient implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected UserDesign $userDesign;
    private GenerateDesignPackageService $service;

    public function __construct(UserDesign $userDesign)
    {
        $this->userDesign = $userDesign;
    }

    public function handle(GenerateDesignPackageService $service):void
    {
        $service->setUserDesign($this->userDesign);
        $service->generateDesignPackage();
    }

    public function tags(): array
    {
        return ['send_package', 'user_design_id:' . $this->userDesign->id];
    }
}
