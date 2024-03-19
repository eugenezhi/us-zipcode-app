<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Services\LookupResultService;
use App\Services\ZipcodeApiService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MainController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected ZipcodeApiService $zipcodeApiService,
        protected LookupResultService $lookupResultService
    ) {}

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        return view('index', ['states' => State::all()]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchZipcodes(Request $request): JsonResponse
    {
        $attributes = $this->validate($request, [
            'city' => 'required|string',
            'state_id' => 'required|int'
        ]);

        $result = $this->searchAndStoreZipcodes($attributes);

        return response()->json($result);
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function searchAndStoreZipcodes(array $attributes): array
    {
        $dbResult = true;
        $result = $this->lookupResultService->getLastByAttributes($attributes);
        if ($result === null) {
            $dbResult = false;
            $stateAbbreviation = State::where('id', $attributes['state_id'])->first()->abbreviation ?? '';
            $apiResult = $this->zipcodeApiService->getZipcodesByCityAndState([...$attributes, ...['state' => $stateAbbreviation]]);
            $result = $this->lookupResultService->create([...$attributes, ...['result' => $apiResult]]);
        }

        return ['result' => $result, 'dbResult' => $dbResult];
    }
}
