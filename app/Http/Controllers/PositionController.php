<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorInvalidArgumentException;
use App\Http\Requests\GeoHashParameterRequest;
use App\Http\Requests\StorePositionRequest;
use App\Http\Resources\PositionListResource;
use App\Http\Resources\PositionResource;
use App\UseCase\PositionUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PositionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePositionRequest $request
     * @return JsonResponse
     */
    public function store(StorePositionRequest $request): JsonResponse
    {
        /** @var PositionUseCase $useCase */
        $useCase = app(PositionUseCase::class);
        try {
            $position = $useCase->createPosition($request);
            $images = $useCase->findImageURLs($position->geoHash);
        } catch (ValidatorInvalidArgumentException $e) {
            return new JsonResponse($e->getErrors()->toArray(), 401);
        } catch (\ValueError|\InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), 401);
        }

        return new JsonResponse(new PositionResource(['position' => $position, 'images' => $images]), 201);
    }

    /**
     * APIでPositionの一覧を表示する
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $useCase = app(PositionUseCase::class);
        $positions = $useCase->findAll();
        return new JsonResponse(new PositionListResource($positions), 200);
    }

    /**
     * APIでそのPositionを表示する
     *
     * @param GeoHashParameterRequest $request
     * @return JsonResponse
     */
    public function show(GeoHashParameterRequest $request): JsonResponse
    {
        $geoHash = $request->getValidatedGeoHash();

        $useCase = app(PositionUseCase::class);
        $position = $useCase->find($geoHash->value);
        $images = $useCase->findImageURLs($geoHash);
        return new JsonResponse(new PositionResource(['position' => $position, 'images' => $images]), 200);
    }


    /**
     * @param GeoHashParameterRequest $request
     * @return Response
     */
    public function destroy(GeoHashParameterRequest $request): Response
    {
        $geoHash = $request->getValidatedGeoHash();
        $useCase = app(PositionUseCase::class);
        $position = $useCase->find($geoHash);
        $useCase->delete($position);

        return new Response(null, 204);
    }
}
