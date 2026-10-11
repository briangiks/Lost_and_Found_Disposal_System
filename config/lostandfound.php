<?php

return [

    // Number of days an unclaimed item may sit in custody before it is due for disposal.
    'disposal_age_days' => (int) env('LNF_DISPOSAL_AGE_DAYS', 90),

    // How many days before the cut-off the "one week to disposal" banner appears.
    'early_warning_days' => (int) env('LNF_EARLY_WARNING_DAYS', 83),

    // Length of the student pickup window once Communications sends the notice.
    'pickup_window_days' => (int) env('LNF_PICKUP_WINDOW_DAYS', 7),

    'office_location' => env('LNF_OFFICE_LOCATION', 'Central Building Phase 1 Basement — Lost & Found Dept'),

    'emails' => [
        'office' => env('LNF_OFFICE_EMAIL', 'lostandfound@strathmore.edu'),
        'communications' => env('LNF_COMMS_EMAIL', 'communications@strathmore.edu'),
        'outreach' => env('LNF_OUTREACH_EMAIL', 'outreach.club@strathmore.edu'),
        // Communications normally re-broadcasts to students themselves; this is the
        // address the automated "1 week left" student notice is sent to (a mailing
        // list / distribution group), since the system has no individual student
        // directory to email one-by-one.
        'students_list' => env('LNF_STUDENTS_LIST_EMAIL', 'students-announce@strathmore.edu'),
    ],

    // Automatically run the disposal cycle (send notices, release batches) on a
    // schedule instead of requiring an officer to click through the workflow.
    // See routes/console.php (Schedule::command(...)) and
    // app/Console/Commands/ProcessDisposalCycle.php.
    'automation_enabled' => env('LNF_AUTOMATION_ENABLED', true),

    'categories' => ['Electronics', 'Personal', 'Clothing', 'Stationery', 'Accessories', 'Bags', 'Documents', 'Other'],

    'locations' => [
        'MSB 13', 'MSB 7', 'LT 3', 'LT 6', 'Student Centre', 'Oval Building',
        'STMB Basement', 'F2', 'F4', 'F5', 'Main Library', 'STMB Library',
        'Gazebo', 'Central Building Phase 1',
    ],

];
