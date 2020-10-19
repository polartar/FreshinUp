<?php

namespace App\Http\Controllers\Foodfleet\Document\Template;

use App\Http\Resources\Foodfleet\Document\Template\Template as Resource;
use App\Models\Foodfleet\Document\Template\Template as Model;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class Templates extends Controller
{
    public function index(Request $request)
    {
        $templates = QueryBuilder::for(Model::class, $request)
            ->allowedFilters([
                'title',
                'status_id',
                'updated_at',
                'updated_by_uuid'
            ])
            ->allowedIncludes([
                'status',
                'updated_by'
            ])
            ->allowedSorts([
                'title',
                'status_id',
                'updated_at',
                'updated_by_uuid'
            ]);

        return Resource::collection($templates->jsonPaginate());
    }

    public function show(Request $request, $uuid)
    {
        $template = QueryBuilder::for(Model::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([
                'status',
                'updated_by'
            ])
            ->firstOrFail();

        return new Resource($template);
    }

    public function update(Request $request, $uuid)
    {
        $item = Model::where('uuid', $uuid)->firstOrFail();
        $rules = [
            'title' => 'string',
            'description' => 'nullable|string',
            'content' => 'string',
            'status_id' => 'exists:document_template_statuses,id'
        ];
        $this->validate($request, $rules);
        $payload = array_merge(
            $request->only(array_keys($rules)),
            [
                'updated_by_uuid' => Auth::id()
            ]
        );
        $item->update($payload);
        return new Resource($item);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'string',
            'description' => 'string',
            'content' => 'string',
            'status_id' => 'exists:document_template_statuses,id'
        ];
        $this->validate($request, $rules);
        $payload = $request->only(array_keys($rules));
        $item = Model::create($payload);
        return new Resource($item);
    }

    public function destroy($uuid)
    {
        $item = Model::where('uuid', $uuid)->firstOrFail();
        $item->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
