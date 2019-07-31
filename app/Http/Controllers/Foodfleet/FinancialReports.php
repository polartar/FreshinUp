<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Reportable as ReportableResource;
use App\Models\Foodfleet\FinancialReport;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use Illuminate\Validation\Rule;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class FinancialReports extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $reports = QueryBuilder::for($user->financialReports()->getQuery())
            ->orderBy('updated_at', 'desc');

        return ReportableResource::collection($reports->jsonPaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return ReportableResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            'name' => 'required',
            'filters' => 'json|required',
            'modifier_1_id' => 'nullable|integer|exists:modifiers,id',
            'modifier_2_id' => 'nullable|integer|exists:modifiers,id'
        ]);

        $inputs = $request->input();
        $inputs['user_id'] = $user->id;
        $report = FinancialReport::create($inputs);

        return new ReportableResource($report);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return ReportableResource
     */
    public function show($id)
    {
        $report = FinancialReport::findOrFail($id);

        return new ReportableResource($report);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return ReportableResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $report = FinancialReport::findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
            'filters' => 'json|required',
            'modifier_1_id' => 'nullable|integer|exists:modifiers,id',
            'modifier_2_id' => 'nullable|integer|exists:modifiers,id'
        ]);

        $inputs = $request->input();
        $report->update($inputs);

        return new ReportableResource($report);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $report = FinancialReport::findOrFail($id);
        $report->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
