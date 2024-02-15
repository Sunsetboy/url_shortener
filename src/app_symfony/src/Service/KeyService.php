<?php

namespace App\Service;

use App\Entity\UrlCode;
use App\Exceptions\UniqueKeyException;
use App\Repository\UrlCodeRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Uid\Uuid;

class KeyService
{
    const MAX_ATTEMPTS_TO_GENERATE_UNIQUE_CODE = 10;

    public function __construct(
        private readonly UrlCodeRepository $keyRepository,
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

    /**
     * @throws UniqueKeyException
     */
    public function generateAndSaveKey(): UrlCode
    {
        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS_TO_GENERATE_UNIQUE_CODE; $attempt++) {
            try {
                $key = $this->generateKey(8);
                $keyRecord = new UrlCode();
                $keyRecord->setCode($key);
                $keyRecord->setIsUsed(false);
                $this->keyRepository->saveKey($keyRecord);
                return $keyRecord;
            } catch (UniqueConstraintViolationException $uniqueKeyException) {
                var_dump("Key " . $key . ' exists, attempt ' . $attempt);
                continue;
            }
        }
        throw new UniqueKeyException('Could not generate unique key');
    }

    protected function generateKey(int $length = 8): string
    {
        $uuid = Uuid::v4();
        return substr($uuid->toBase58(), 0, $length);
    }
}
