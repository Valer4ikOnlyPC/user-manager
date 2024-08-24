<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Model;

use UserManager\Core\Common\Exception\InvalidArgumentException;

class Money
{
    public const RUB_CURRENCY = 'RUB';

    public const USD_CURRENCY = 'USD';

    /**
     * @var int
     */
    protected $valueInCentiUnits;

    /**
     * @var string
     */
    protected $currency;

    public function __construct(int $valueInCentiUnits, string $currency)
    {
        $this->setValueInCentiUnits($valueInCentiUnits);
        $this->setCurrency($currency);
    }

    private function setValueInCentiUnits(int $valueInCentiUnits): void
    {
        if (0 > $valueInCentiUnits) {
            throw new InvalidArgumentException("Значение цены не может быть отрицательным");
        }
        $this->valueInCentiUnits = $valueInCentiUnits;
    }

    public function valueInCentiUnits(): int
    {
        return $this->valueInCentiUnits;
    }

    private function setCurrency(string $currency): void
    {
        $r = new \ReflectionClass(Money::class);

        $rCurrencyConstants = array_filter(
            $r->getConstants(),
            function (string $name) {
                return 1 === preg_match('/_CURRENCY$/', $name);
            },
            ARRAY_FILTER_USE_KEY
        );

        if (false === in_array($currency, $rCurrencyConstants, true)) {
            throw new InvalidArgumentException(sprintf('Неверный идентификатор валюты - "%s".', $currency));
        }

        $this->currency = $currency;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function equals(self $comparedMoney): bool
    {
        return $this->currency() === $comparedMoney->currency() && $this->valueInCentiUnits() === $comparedMoney->valueInCentiUnits();
    }

    public function isNull(): bool
    {
        if ($this->valueInCentiUnits === null && $this->currency === null) {
            return true;
        }
        return false;
    }
}
