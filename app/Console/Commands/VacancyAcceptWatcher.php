<?php

namespace App\Console\Commands;

use App\Models\CoreEngine\LogicModels\Vacancy\VacancyLogic;
use Illuminate\Console\Command;

class VacancyAcceptWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:watch-vacancy-accept';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверяет, не истек ли срок принятия вакансии в работу юристом, или срок принятия готовой работы клиентом';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $vacancyLogic = new VacancyLogic();
        $vacancyLogic->triggerVacancyApprovingWhenClientNotAccept();
        $vacancyLogic->triggerVacancyCancellationWhenLawyerNotAccept();
    }
}
