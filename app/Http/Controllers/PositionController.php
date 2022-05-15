<?php

namespace App\Http\Controllers;

use App\Domain\ValueObject\Position\GeoHash;
use App\Exceptions\ValidatorInvalidArgumentException;
use App\Http\Requests\StorePositionRequest;
use App\Http\Resources\PositionListResource;
use App\Http\Resources\PositionResource;
use App\UseCase\PositionUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PositionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePositionRequest $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(StorePositionRequest $request): JsonResponse|RedirectResponse
    {
        $useCase = new PositionUseCase();
        try {
            $position = $useCase->createPosition($request);
            $images = $useCase->findImageURLs($position->geoHash);
        } catch (ValidatorInvalidArgumentException $e) {
            return (new RedirectResponse(''))->withErrors($e->getErrors())->withInput();
        } catch (\ValueError|\InvalidArgumentException $e) {
            return (new RedirectResponse(''))->withErrors($e->getMessage())->withInput();
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
        $useCase = new PositionUseCase();
        $positions = $useCase->findAll();
        return new JsonResponse(new PositionListResource($positions), 200);
    }

    /**
     * APIでそのPositionを表示する
     *
     * @param GeoHash $geoHash
     * @return JsonResponse
     */
    public function show(GeoHash $geoHash): JsonResponse
    {
        $useCase = new PositionUseCase();
        $position = $useCase->find($geoHash->value);
        $images = $useCase->findImageURLs($geoHash);
        return new JsonResponse(new PositionResource(['position' => $position, 'images' => $images]), 200);
    }


    /**
     * @param GeoHash $geoHash
     * @return Response
     */
    public function destroy(GeoHash $geoHash): Response
    {
        $useCase = new PositionUseCase();
        $position = $useCase->find($geoHash);
        $useCase->delete($position);

        return new Response(null, 204);
    }
}
