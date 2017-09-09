<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

use DateTime;

class MemPoolEntry
{
    /** @var int */
    private $ancestorCount = -1;

    /** @var float */
    private $ancestorFees = -1;

    /** @var int */
    private $ancestorSize = -1;

    /** @var float */
    private $currentPriority = -1;

    /** @var string[] */
    private $depends = [];

    /** @var int */
    private $descendantCount = -1;

    /** @var float */
    private $descendantFees = -1;

    /** @var int */
    private $descendantSize = -1;

    /** @var float */
    private $fee = -1;

    /** @var int */
    private $height = -1;

    /** @var float */
    private $modifiedFee = -1;

    /** @var int */
    private $size = -1;

    /** @var float */
    private $startingPriority = -1;

    /** @var DateTime */
    private $time = null;

    /**
     * @param int      $height
     * @param int      $size
     * @param float    $fee
     * @param float    $modifiedFee
     * @param int      $time
     * @param float    $startingpriority
     * @param float    $currentPriority
     * @param int      $descendantCount
     * @param float    $descendantSize
     * @param float    $descendantFees
     * @param int      $ancestorCount
     * @param float    $ancestorSize
     * @param float    $ancestorFees
     * @param string[] $depends
     */
    private function __construct(
        int $height,
        int $size,
        float $fee,
        float $modifiedFee,
        int $time,
        float $startingpriority,
        float $currentPriority,
        int $descendantCount,
        float $descendantSize,
        float $descendantFees,
        int $ancestorCount,
        float $ancestorSize,
        float $ancestorFees,
        array $depends
    ) {
        $this->height = $height;
        $this->size = $size;
        $this->fee = $fee;
        $this->modifiedFee = $modifiedFee;
        $this->time = DateTime::createFromFormat('U', (string)$time);
        $this->startingPriority = $startingpriority;
        $this->currentPriority = $currentPriority;
        $this->descendantCount = $descendantCount;
        $this->descendantSize = $descendantSize;
        $this->descendantFees = $descendantFees;
        $this->ancestorCount = $ancestorCount;
        $this->ancestorSize = $ancestorSize;
        $this->ancestorFees = $ancestorFees;
        $this->depends = $depends;
    }

    /**
     * @param array $memPoolInfo
     *
     * @return MemPoolEntry
     */
    public static function fromArray(array $memPoolInfo): self
    {
        return new static(
            $memPoolInfo['height'] ?? -1,
            $memPoolInfo['size'] ?? -1,
            $memPoolInfo['fee'] ?? -1,
            $memPoolInfo['modifiedfee'] ?? -1,
            $memPoolInfo['time'] ?? -1,
            $memPoolInfo['startingpriority'] ?? -1,
            $memPoolInfo['currentpriority'] ?? -1,
            $memPoolInfo['descendantcount'] ?? -1,
            $memPoolInfo['descendantsize'] ?? -1,
            $memPoolInfo['descendantfees'] ?? -1,
            $memPoolInfo['ancestorcount'] ?? -1,
            $memPoolInfo['ancestorsize'] ?? -1,
            $memPoolInfo['ancestorfees'] ?? -1,
            $memPoolInfo['depends'] ?? []
        );
    }

    /**
     * @return int
     */
    public function getAncestorCount(): int
    {
        return $this->ancestorCount;
    }

    /**
     * @return float
     */
    public function getAncestorFees(): float
    {
        return $this->ancestorFees;
    }

    /**
     * @return float
     */
    public function getAncestorSize(): float
    {
        return $this->ancestorSize;
    }

    /**
     * @return float
     */
    public function getCurrentPriority(): float
    {
        return $this->currentPriority;
    }

    /**
     * @return \string[]
     */
    public function getDepends(): array
    {
        return $this->depends;
    }

    /**
     * @return int
     */
    public function getDescendantCount(): int
    {
        return $this->descendantCount;
    }

    /**
     * @return float
     */
    public function getDescendantFees(): float
    {
        return $this->descendantFees;
    }

    /**
     * @return float
     */
    public function getDescendantSize(): float
    {
        return $this->descendantSize;
    }

    /**
     * @return float
     */
    public function getFee(): float
    {
        return $this->fee;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return float
     */
    public function getModifiedFee(): float
    {
        return $this->modifiedFee;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return float
     */
    public function getStartingPriority(): float
    {
        return $this->startingPriority;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }
}
