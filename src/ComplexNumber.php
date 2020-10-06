<?php

declare(strict_types=1);

namespace App;

class ComplexNumber
{
    private float $real;
    private float $imaginary;
    private bool $isNan;
    private bool $isInfinite;

    public static function createInfinite(): self
    {
        return new self(INF, INF);
    }

    public static function createNan(): self
    {
        return new self(NAN, NAN);
    }

    public static function createZero(): self
    {
        return new self(0.0, 0.0);
    }

    public function __construct(float $real, float $imaginary = 0.0)
    {
        $this->real = $real;
        $this->imaginary = $imaginary;

        $this->isNan = \is_nan($real) || \is_nan($imaginary);
        $this->isInfinite = !$this->isNan && (\is_infinite($real) || \is_infinite($imaginary));
    }

    public function add(self $y): self
    {
        if ($this->isNan || $y->isNan()) {
            return self::createNan();
        }

        return new self(
            $this->real + $y->getReal(),
            $this->imaginary + $y->getImaginary()
        );
    }

    public function subtract(self $y): self
    {
        if ($this->isNan || $y->isNan) {
            return self::createNan();
        }

        return new self(
            $this->real - $y->getReal(),
            $this->imaginary - $y->getImaginary()
        );
    }

    public function multiply(self $y): self
    {
        if ($this->isNan || $y->isNan()) {
            return self::createNan();
        }

        if (\is_infinite($this->real)
            || \is_infinite($this->imaginary)
            || \is_infinite($y->getReal())
            || \is_infinite($y->getImaginary())
        ) {
            return self::createInfinite();
        }

        return new self(
            $this->real * $y->getReal() - $this->imaginary * $y->getImaginary(),
            $this->real * $y->getImaginary() + $this->imaginary * $y->getReal()
        );
    }

    public function divide(self $y): self
    {
        if ($this->isNan || $y->isNan()) {
            return self::createNan();
        }

        $yReal = $y->getReal();
        $yImaginary = $y->getImaginary();

        if (0.0 === $yReal && 0.0 === $yImaginary) {
            return self::createNan();
        }

        if (!$this->isInfinite && $y->isInfinite()) {
            return self::createZero();
        }

        if (\abs($yReal) < \abs($yImaginary)) {
            $yRatio = $yReal / $yImaginary;
            $yDenominator = $yReal * $yRatio + $yImaginary;

            return new self(
                ($this->real * $yRatio + $this->imaginary) / $yDenominator,
                ($this->imaginary * $yRatio - $this->real) / $yDenominator
            );
        }

        $yRatio = $yImaginary / $yReal;
        $yDenominator = $yImaginary * $yRatio + $yReal;

        return new self(
            ($this->imaginary * $yRatio + $this->real) / $yDenominator,
            ($this->imaginary - $this->real * $yRatio) / $yDenominator
        );
    }

    public function getReal(): float
    {
        return $this->real;
    }

    public function getImaginary(): float
    {
        return $this->imaginary;
    }

    public function isNan(): bool
    {
        return $this->isNan;
    }

    public function isInfinite(): bool
    {
        return $this->isInfinite;
    }
}
