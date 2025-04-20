<?php

namespace App\Services;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;

class ReportService
{

    public function index(): array
    {
        // check if user has client role
        if (auth()->user()->hasRole('client')) {
            // return reports belong to auth client user
            $reports = Report::query()->where('user_id', auth()->user()->id);
        } else {
            // Retrive all reports
            $reports = Report::query();
        }
        $reports = $reports->latest()->get();

        // Check if reports are empty
        if ($reports->isEmpty()) {
            $message = "there are not reports at the moment";
        } else {
            $message = "Reports index successfully";
        }
        return ['message' => $message, 'reports' => $reports];

    }
    public function create($request): array
    {
        $report = Report::query()->create([
            'project' => $request['project'],
            'location' => $request['location'],
            'user_id' => auth()->user()->id,
            'report_date' => $request['report_date'] ?? Carbon::today()
        ]);

        $message = "report created Successfully";

        return ['message' => $message, 'report' => $report];
    }
    public function update($request, $id): array
    {
        if (!auth()->user()->can('update report')) {
            abort(403, 'You do not have permission to update this report.');
        }
        $report = Report::query()->find($id);
        if (!is_null($report)) {
            if (auth()->user()->hasRole('admin')) {
                Report::where('id', $id)->update([
                    'project' => $request->input('project'),
                    'location' => $request['location'],
                    'report_date' => $request['report_date']
                ]);
                $report = Report::query()->find($id);
                $message = "report update successfully";
                $code = 200;
            } else {
                $message = "unpermissions";
                $code = 400;
            }


        } else {
            $message = "report not dount";
            $code = 404;
        }
        return ['message' => $message, 'report' => $report, 'code' => $code];
    }

    public function destroy($id)
    {
        $report = Report::query()->find($id);
        if (!is_null($report)) {
            $report = $report->delete();
            $message = "report deleted successfully";
            $code = 200;

        } else {
            $message = "report not dount";
            $code = 404;
        }
        return ['message' => $message, 'report' => $report, 'code' => $code];
    }
}

?>