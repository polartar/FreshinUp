<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Actions\CreateDocument;
use App\Actions\UpdateDocument;
use App\Http\Resources\Foodfleet\Document\Document as DocumentResource;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo as FilterGreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo as FilterLessThanOrEqualTo;
use App\Models\Foodfleet\Document;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\User;


use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Documents extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $documents = QueryBuilder::for(Document::class, $request)
            ->with('owner')
            ->with('assigned')
            ->allowedSorts([
                'title',
                'type',
                'status',
                'created_at',
                'expiration_at',
                'created_by'
            ])
            ->allowedFilters([
                'title',
                Filter::exact('type'),
                Filter::exact('status'),
                Filter::exact('assigned_uuid'),
                Filter::custom('expiration_from', FilterGreaterThanOrEqualTo::class, 'expiration_at'),
                Filter::custom('expiration_to', FilterLessThanOrEqualTo::class, 'expiration_at')
            ]);

        return DocumentResource::collection($documents->jsonPaginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return DocumentResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CreateDocument $action)
    {
        $user = $request->user();

        $this->validate($request, [
            'title' => 'required',
            'type' => 'integer|required',
            'status' => 'integer|required',
            'description' => 'required',
            'notes' => 'string',
            'expiration_at' => 'date'
        ]);

        $inputs = $request->input();
        $inputs['created_by_uuid'] = $user->uuid;

        $document = $action->execute($inputs);

        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return DocumentResource
     */
    public function show($id)
    {
        $document = Document::with('owner')->with('assigned')->findOrFail($id);

        return new DocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return DocumentResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id, UpdateDocument $action)
    {
        $this->validate($request, [
            'title' => 'string',
            'type' => 'integer',
            'status' => 'integer',
            'description' => 'string',
            'notes' => 'string',
            'expiration_at' => 'date'
        ]);

        $inputs = $request->input();
        $inputs['id'] = $id;

        $document = $action->execute($inputs);

        return new DocumentResource($document);
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
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
