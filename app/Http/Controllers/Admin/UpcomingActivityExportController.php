<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UpcomingActivitiesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UpcomingActivityExportController extends Controller
{
    public function __invoke()
    {
        return Excel::download(
            new UpcomingActivitiesExport,
            'upcoming-activities-'.now()->format('d-m-Y').'.xlsx'
        );
    }
}
