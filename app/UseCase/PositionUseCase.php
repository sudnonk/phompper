<?php

namespace App\UseCase;

use App\Domain\Entity\Position\Position;
use App\Domain\ValueObject\Image\ImageURL;
use App\Domain\ValueObject\Position\GeoHash;
use App\Http\Requests\StorePositionRequest;
use App\Infrastructure\Database\ImageRepositoryInterface;
use App\Infrastructure\Database\PositionRepositoryInterface;

class PositionUseCase
{
    public function __construct(
        protected PositionRepositoryInterface $positionRepository,
        protected ImageRepositoryInterface $imageRepository
    ) {

    }

    /**
     * $requestからPositionとImagePathsを生成し、データベースとストレージに保存する
     *
     * @param StorePositionRequest $request
     * @return Position
     */
    public function createPosition(StorePositionRequest $request): Position
    {
        $geoHash = $request->getGeoHash();
        $position = $this->find($geoHash);
        //そのGeoHashのPositionが存在しなければ作成する。すでに存在している場合はPositionDetailの追加になる
        if ($position === null) {
            $position = new Position($geoHash, []);
        }
        $position = $request->fillPositionDetail($position);

        $images = $request->makeImages($position);

        $this->positionRepository->savePosition($position);
        foreach ($images as $tmpPath => $image) {
            $this->imageRepository->saveImage($tmpPath, $image);
        }

        return $position;
    }

    /**
     * $geoHashのPositionをデータベースから取得し、Positionオブジェクトを生成して返す
     *
     * @param GeoHash $geoHash
     * @return Position|null
     */
    public function find(GeoHash $geoHash): ?Position
    {
        return $this->positionRepository->find($geoHash);
    }

    /**
     * $geoHashのImagePathをデータベースから取得し、そのURLを取得してImageURLオブジェクトの配列を生成して返す
     *
     * @param GeoHash $geoHash
     * @return ImageURL[]
     */
    public function findImageURLs(GeoHash $geoHash): array
    {
        return $this->imageRepository->findImages($geoHash);
    }

    /**
     * データベースに保存されている全てのPositionを取得し、Positionオブジェクトの配列を生成して返す
     *
     * @return Position[]
     */
    public function findAll(): array
    {
        return $this->positionRepository->findAll();
    }

    //todo: GeoHashによる範囲検索により、全てではなく絞ったPositionを取得したい（Google Mapsで表示されている範囲内のみのPositionを検索するイメージ）

    /**
     * PositionのgeoHashに紐づくPositionとImagePathをデータベースから削除し、ストレージからも削除する。
     *
     * @param Position $position
     * @return void
     */
    public function delete(Position $position): void
    {
        $images = $this->imageRepository->findImages($position->geoHash);
        foreach ($images as $image) {
            $this->imageRepository->deleteImage($image);
        }
        $this->positionRepository->delete($position->geoHash);
    }
}
