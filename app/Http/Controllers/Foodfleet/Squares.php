<?php


namespace App\Http\Controllers\Foodfleet;

use App\Actions\AssociateSquareTokens;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSquaresRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Squares extends Controller
{
    /**
     * Check authorization code from Square
     *
     * @param AssociateSquareTokens $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(StoreSquaresRequest $request, AssociateSquareTokens $action)
    {
        $data = [
            'code' => $request->input('code'),
            'user_id' => $request->user()->id
        ];

        try {
            $action->execute($data);
        } catch (\Exception $e) {
            return response()->json(['result' => false], SymfonyResponse::HTTP_BAD_REQUEST);
        }
        return response()->json(['result' => true], SymfonyResponse::HTTP_CREATED);
    }
}
