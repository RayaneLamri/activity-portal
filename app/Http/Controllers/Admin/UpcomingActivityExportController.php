<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UpcomingActivitiesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;

class UpcomingActivityExportController extends Controller
{
    public function __invoke()
    {
        return Excel::download(
            new UpcomingActivitiesExport(),
            'upcoming-activities-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
