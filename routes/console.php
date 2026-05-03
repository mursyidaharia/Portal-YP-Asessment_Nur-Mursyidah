<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('exams:publish-scheduled')->everyMinute();