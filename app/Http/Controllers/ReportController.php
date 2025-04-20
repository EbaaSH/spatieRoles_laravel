<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportCreateRequest;
use App\Http\Requests\ReportUpdateRequest;
use App\Http\Responses\Response;
use App\Services\ReportService;
use Illuminate\Http\Request;

use Throwable;

class ReportController extends Controller
{
    private ReportService $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $data = [];
        try {
            $data = $this->reportService->index();
            return Response::Success($data['reports'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function create(ReportCreateRequest $request)
    {
        $data = [];
        try {
            $data = $this->reportService->create($request);
            return Response::Success($data['report'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function update(ReportUpdateRequest $request, $id)
    {
        $data = [];
        try {
            $data = $this->reportService->update($request, $id);
            return Response::Success($data['report'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function destroy($id)
    {
        $data = [];
        try {
            $data = $this->reportService->destroy($id);
            return Response::Success($data['reports'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

}
