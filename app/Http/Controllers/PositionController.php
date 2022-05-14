<?php

namespace App\Http\Controllers;

use App\Domain\Entity\Position\Position;
use App\Exceptions\ValidatorInvalidArgumentException;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\UseCase\PositionUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        //
    }

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
     * APIでそのPositionを表示する
     *
     * @param Position $position
     * @return JsonResponse
     */
    public function show(Position $position): JsonResponse
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Infrastructure\Models\Position $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
