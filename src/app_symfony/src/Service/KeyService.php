<?php

namespace App\Service;

use App\Entity\Key;
use App\Repository\KeyRepository;
use Symfony\Component\Uid\Uuid;

class KeyService
{

    public function __construct(
        private readonly KeyRepository $keyRepository,
    )
    {

    }

    public function generateAndSaveKeys(int $keysNumber): int
    {
        for ($k = 0; $k < $keysNumber; $k++) {
            $this->generateAndSaveKey();
        }
        return $k;
    }

    public function generateAndSaveKey(): Key
    {
        $key = $this->generateKey(8);
        $keyRecord = new Key();
        $keyRecord->setCode($key);
        $keyRecord->setIsUsed(false);
        $this->keyRepository->saveKey($keyRecord);
        return $keyRecord;
    }

    protected function generateKey(int $length = 8): string
    {
        $uuid = Uuid::v4();
        return substr($uuid->toBase58(), 0, $length);
    }
}
