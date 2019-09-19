<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Document as DocumentResource;
use App\Filters\Document\LessExpirationDate as FilterLessExpirationDate;
use App\Filters\Document\OverExpirationDate as FilterOverExpirationDate;
use App\Models\Foodfleet\Document;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;


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
        $user = $request->user();

        $documents = QueryBuilder::for(Document::class, $request)
            ->where('created_by', $user->uuid)
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
                'type',
                'status',
                Filter::custom('expiration_from', FilterLessExpirationDate::class),
                Filter::custom('expiration_to', FilterOverExpirationDate::class)
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
    public function store(Request $request)
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
        $inputs['created_by'] = $user->uuid;
        $document = Document::create($inputs);

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
        $document = Document::findOrFail($id);

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
    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $this->validate($request, [
            'title' => 'string',
            'type' => 'integer',
            'status' => 'integer',
            'description' => 'string',
            'notes' => 'string',
            'expiration_at' => 'date'
        ]);

        $inputs = $request->input();
        $document->update($inputs);

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
