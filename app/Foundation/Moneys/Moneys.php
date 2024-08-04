<?php

namespace App\Foundation\Moneys;

use App\Facades\Settings;
use JetBrains\PhpStorm\Pure;
use Money\Money;

class Moneys
{
    /**
     */
    private mixed $amount;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var int|mixed
     */
    private int $precision;

    /**
     * @var int|mixed
     */
    private int $decimal;

    /**
     * @var array|string[]
     */
    private static array $currencySymbols = [
        'USD' => '$',    // United States Dollar
        'EUR' => '€',    // Euro
        'GBP' => '£',    // British Pound Sterling
        'JPY' => '¥',    // Japanese Yen
        'AUD' => 'A$',   // Australian Dollar
        'CAD' => 'C$',   // Canadian Dollar
        'CHF' => 'CHF',  // Swiss Franc
        'CNY' => '¥',    // Chinese Yuan
        'SEK' => 'kr',   // Swedish Krona
        'NZD' => 'NZ$',  // New Zealand Dollar
        'MXN' => 'MX$',  // Mexican Peso
        'SGD' => 'S$',   // Singapore Dollar
        'HKD' => 'HK$',  // Hong Kong Dollar
        'NOK' => 'kr',   // Norwegian Krone
        'KRW' => '₩',    // South Korean Won
        'TRY' => '₺',    // Turkish Lira
        'RUB' => '₽',    // Russian Ruble
        'INR' => '₹',    // Indian Rupee
        'BRL' => 'R$',   // Brazilian Real
        'ZAR' => 'R',    // South African Rand
        'PLN' => 'zł',   // Polish Zloty
        'PHP' => '₱',    // Philippine Peso
        'CZK' => 'Kč',   // Czech Koruna
        'MYR' => 'RM',   // Malaysian Ringgit
        'IDR' => 'Rp',   // Indonesian Rupiah
        'HUF' => 'Ft',   // Hungarian Forint
        'THB' => '฿',    // Thai Baht
        'AED' => 'د.إ',  // United Arab Emirates Dirham
        'SAR' => '﷼',    // Saudi Riyal
        'QAR' => 'ر.ق',  // Qatari Riyal
        'EGP' => 'EGP',    // Egyptian Pound
        'VND' => '₫',    // Vietnamese Dong
        'BDT' => '৳',    // Bangladeshi Taka
        'PKR' => '₨',    // Pakistani Rupee
        'KWD' => 'د.ك',  // Kuwaiti Dinar
        'BHD' => 'ب.د',  // Bahraini Dinar
        'OMR' => 'ر.ع.', // Omani Rial
        'JOD' => 'د.ا',  // Jordanian Dinar
        'CLP' => 'CLP$', // Chilean Peso
        'COP' => 'COP$', // Colombian Peso
        'PEN' => 'S/',   // Peruvian Sol
        'ARS' => '$',    // Argentine Peso
        'UYU' => '$U',   // Uruguayan Peso
        'RON' => 'lei',  // Romanian Leu
        'BGN' => 'лв',   // Bulgarian Lev
        'HRK' => 'kn',   // Croatian Kuna
        'DKK' => 'kr',   // Danish Krone
        'ISK' => 'kr',   // Icelandic Króna
        // Add more currency symbols as needed
    ];

    public function __construct(
        int $amount,
        string $currency = null,
        int $precision = 2,
        int $decimal = 2

    ) {
        $this->precision = $precision;
        $this->decimal = $decimal;
        $this->currency = $currency ?? 'EGP'; // Use Settings::currency() if $currency is null
        $this->setAmount($amount);
    }

    /**
     * @return int
     */
    private function getDecimal(): int
    {
        return (int) 1 . str_repeat('0', $this->decimal);
    }

    /**
     * @param $amount
     */
    private function setAmount($amount): void
    {
        $this->amount = round($amount / $this->getDecimal(), $this->precision);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param Money $money
     */
    public function add(
        Money $money
    ): void
    {
        if ($this->currency !== $money->getCurrency()) {
            throw new \RuntimeException("Currency mismatch");
        }

        $this->amount = round($this->amount + $money->getAmount(), $this->precision);
    }

    /**
     * @param Money $money
     */
    public function subtract(
        Money $money
    ): void
    {
        if ($this->currency !== $money->getCurrency()) {
            throw new \RuntimeException("Currency mismatch");
        }

        $this->amount = round($this->amount - $money->getAmount(), $this->precision);
    }

    /**
     * @param $factor
     */
    public function multiply(
        $factor
    ): void
    {
        $this->amount = round($this->amount * $factor, $this->precision);
    }

    /**
     * @param $divisor
     */
    public function divide(
        $divisor
    ): void
    {
        if ($divisor === 0) {
            throw new \RuntimeException("Division by zero");
        }
        $this->amount = round($this->amount / $divisor, $this->precision);
    }

    /**
     * @return string
     */
    final public function format(): string
    {
        $symbol = self::$currencySymbols[$this->currency] ?? $this->currency;
        return $symbol . ' ' . number_format($this->amount, $this->precision, ',', '.');
    }

    /**
     * @return int
     */
    #[Pure] final public function amount(): int
    {
        return $this->getAmount();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }
}
