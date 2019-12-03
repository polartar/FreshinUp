<?php

namespace App\Http\Controllers\Foodfleet;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\CreateMessage;
use App\Models\Foodfleet\Message;
use App\Http\Resources\Foodfleet\Message as MessageResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class Messages extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $messages = QueryBuilder::for(Message::class, $request)
            ->allowedIncludes(['owner', 'recipient'])
            ->allowedFilters([
                Filter::exact('uuid'),
                Filter::exact('event_uuid'),
                Filter::exact('store_uuid')
            ]);

        return MessageResource::collection($messages->jsonPaginate());
    }

    /**
     * Generates an empty document.
     *
     * @return DocumentResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function showNewRecommendation()
    {
        return new MessageResource(
            Message::make()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateMessage $action)
    {
        $user = $request->user();

        $this->validate($request, [
            'content' => 'required',
            'event_uuid' => 'string|exists:events,uuid',
            'store_uuid' => 'string|exists:stores,uuid'
        ]);

        $inputs = $request->input();
        $inputs['created_by_uuid'] = $user->uuid;

        $message = $action->execute($inputs);

        return new MessageResource($message);
    }
}
