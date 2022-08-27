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
        if ($request->isValidationFailed()) {
            return new JsonResponse($request->getValidationErrorMsg()->toArray(), 400);
        }

        /** @var PositionUseCase $useCase */
        $useCase = app(PositionUseCase::class);
        try {
            $position = $useCase->createPosition($request);
            $images = $useCase->findImageURLs($position->geoHash);
        } catch (ValidatorInvalidArgumentException $e) {
            return new JsonResponse($e->getErrors()->toArray(), 400);
        } catch (\ValueError|\InvalidArgumentException $e) {
            return new JsonResponse($e->getMessage(), 400);
        } catch (\Error|\Exception $e) {
            return new JsonResponse($e->getMessage(), 500);
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
        try {
            $positions = $useCase->findAll();
        } catch (\Exception|\Error $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
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
        if ($request->isValidationFailed()) {
            return new JsonResponse($request->getValidationErrorMsg()->toArray(), 400);
        }

        $useCase = app(PositionUseCase::class);
        try {
            $geoHash = $request->getValidatedGeoHash();
            $position = $useCase->find($geoHash);
            $images = $useCase->findImageURLs($geoHash);
        } catch (\Exception|\Error $e) {
            return new JsonResponse($e->getMessage(), 500);
        }
        if ($position === null) {
            return new JsonResponse("Not found", 404);
        } else {
            return new JsonResponse(new PositionResource(['position' => $position, 'images' => $images]), 200);
        }
    }


    /**
     * @param GeoHashParameterRequest $request
     * @return Response|JsonResponse
     */
    public function destroy(GeoHashParameterRequest $request): Response|JsonResponse
    {
        if ($request->isValidationFailed()) {
            return new JsonResponse($request->getValidationErrorMsg()->toArray(), 400);
        }
        try {
            $geoHash = $request->getValidatedGeoHash();
            $useCase = app(PositionUseCase::class);
            $position = $useCase->find($geoHash);
            $useCase->delete($position);
        } catch (\Exception|\Error $e) {
            return new JsonResponse($e->getMessage(), 500);
        }

        return new Response(null, 204);
    }
}
