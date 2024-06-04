<?php

namespace App\Jobs;

use App\Models\CoreEngine\LogicModels\User\UserLogic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordLastActivityJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;


    private $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->userId = auth()->id();
    }

    public function uniqueId()
    {
        return $this->userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new UserLogic())->setUserOnlineTimestamp();
    }
}
