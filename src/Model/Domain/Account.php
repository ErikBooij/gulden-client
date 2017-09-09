<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class Account
{
    /** @var int */
    private $HDindex = -1;

    /** @var string */
    private $label = '';

    /** @var string */
    private $type = '';

    /** @var string */
    private $UUID = '';

    /**
     * @param string $UUID
     * @param string $label
     * @param string $type
     * @param int    $HDindex
     */
    private function __construct(string $UUID, string $label, string $type = 'HD', int $HDindex = -1)
    {
        $this->HDindex = $HDindex;
        $this->label = $label;
        $this->type = $type;
        $this->UUID = $UUID;
    }

    /**
     * @param array $accountInfo
     *
     * @return Account
     */
    public static function fromArray(array $accountInfo): self
    {
        return new static(
            $accountInfo['UUID'] ?? '',
            $accountInfo['label'] ?? '',
            $accountInfo['type'] ?? '',
            $accountInfo['HDindex'] ?? -1
        );
    }

    /**
     * @return int
     */
    public function getHDindex(): int
    {
        return $this->HDindex;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->UUID;
    }
}
