<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class FailedPaymentDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:payment-deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pengecekan data pesanan, jika melebihi dari 24 jam maka akan gagal';

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
        foreach (DB::table('book_user')->get() as $bookUser) {
            $now           = Carbon::now();
            $deadline      = $bookUser->payment_deadline;

            if ($now->greaterThan($deadline) && $bookUser->payment_status == 'waiting_for_confirmation') {
                $update     = array('payment_status' => 'failed');

                $bookUser->update($update);
            }
        }

        return 0;
    }
}
