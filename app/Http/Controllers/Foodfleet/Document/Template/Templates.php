<?php

namespace App\Http\Controllers\Foodfleet\Document\Template;

use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Resources\Foodfleet\Document\Template\Template as Resource;
use App\Models\Foodfleet\Document\Template\Template as Model;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
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
                Filter::custom('updated_by_uuid', BelongsToWhereInUuidEquals::class, 'updateBy')
            ])
            ->allowedIncludes([
                'status',
                'updatedBy'
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
                'updated_by_uuid'
            ])
            ->firstOrFail();

        return new Resource($template);
    }

    public function update(Request $request, $uuid)
    {
        $item = Model::where('uuid', $uuid)->firstOrFail();
        $rules = [
            'title' => 'string',
            'status_id' => 'exists:document_template_statuses,id',
            'updated_by_uuid' => 'exists:users,uuid'
        ];
        $payload = $request->only(array_keys($rules));
        $item->update($payload);
        return new Resource($item);
    }

    public function destroy($uuid)
    {
        $item = Model::where('uuid', $uuid)->firstOrFail();
        $item->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
