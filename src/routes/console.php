<?php

use App\Jobs\SendDailySalesReport;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new SendDailySalesReport)->dailyAt('23:00');
