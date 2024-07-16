<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

// Inspire command that runs hourly
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule the task to clean the waiting_lists table every 6 hours
Schedule::call(function () {
    DB::table('waiting_lists')->delete();
})->name('clean-waiting-lists')->everySixHours()->withoutOverlapping();
