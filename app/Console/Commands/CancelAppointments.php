<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Carbon\Carbon;
use App\Models\AppointmentTable;

class CancelAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel expired appointments';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info(" - Initializing cancel expired appointments script...");

        // Prepare the data
        $currentDateTime = Carbon::now();
        $appointments = AppointmentTable::where('iscancel', 0)
            ->whereDate('adate', '<=', $currentDateTime)
            ->get();
        $output = new ConsoleOutput();
        $progressBar = new ProgressBar($output, count($appointments));

        // No expired appointments
        if (count($appointments) == 0) {
            return $this->output->write(' No found expired appointment');
        }

        $progressBar->start();

        // Process expired appointments
        $processed = 0;
        foreach ($appointments as $appointment) {
            list($startTime, $endTime) = explode(' - ', $appointment->atime);
            $startTime = $appointment->adate . ' ' . Carbon::createFromFormat('g:i A', $startTime)->format('H:i:s');
            $endTime = $appointment->adate . ' '. Carbon::createFromFormat('g:i A', $endTime)->format('H:i:s');

            // After/Expire Schedule
            if ($currentDateTime->gt(Carbon::parse($endTime))) {
                $expiredAppointment = AppointmentTable::find($appointment->eid);

                // Update
                $expiredAppointment->update([
                    'iscancel' => 1,
					'appstatus' => 1,
                    'canceldt' => $currentDateTime
                ]);

                $processed++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        print "\n";

        // We're done
        $this->output->write(" Successfully cancelled $processed expired appointment(s).");
    }
}
