<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('invoices:generate')->monthlyOn(1, '00:01');
Schedule::command('invoices:mark-overdue')->dailyAt('08:00');
Schedule::command('reminders:rent')->dailyAt('09:00');
Schedule::command('leases:expire')->daily();
