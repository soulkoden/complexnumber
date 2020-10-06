<?php

declare(strict_types=1);

namespace App\Tests;

use App\ComplexNumber;
use PHPUnit\Framework\TestCase;

class ComplexNumberTest extends TestCase
{
    public function testConstructor(): void
    {
        $x = new ComplexNumber(3.0, 4.0);

        self::assertSame(3.0, $x->getReal());
        self::assertSame(4.0, $x->getImaginary());
    }

    public function testConstructorNan(): void
    {
        $x = new ComplexNumber(3.0, NAN);

        self::assertTrue($x->isNan());

        $x = new ComplexNumber(NAN, 4.0);

        self::assertTrue($x->isNan());

        $x = new ComplexNumber(3.0, 4.0);

        self::assertFalse($x->isNan());
    }

    public function testAdd(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = new ComplexNumber(5.0, 6.0);
        $z = $x->add($y);

        self::assertSame(8.0, $z->getReal());
        self::assertSame(10.0, $z->getImaginary());
    }

    public function testAddNan(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = ComplexNumber::createNan();
        $z = $x->add($y);

        self::assertTrue($z->isNan());

        $y = new ComplexNumber(1.0, NAN);
        $z = $x->add($y);

        self::assertTrue($z->isNan());
    }

    public function testAddInfinite(): void
    {
        $x = new ComplexNumber(1.0, 1.0);
        $y = new ComplexNumber(INF, 0.0);
        $z = $x->add($y);

        self::assertSame(1.0, $z->getImaginary());
        self::assertSame(INF, $z->getReal());

        $x = new ComplexNumber(-INF, 0);
        $z = $x->add($y);

        self::assertNan($z->getReal());
    }

    public function testSubtract(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = new ComplexNumber(5.0, 6.0);
        $z = $x->subtract($y);

        self::assertSame(-2.0, $z->getReal());
        self::assertSame(-2.0, $z->getImaginary());
    }

    public function testSubtractNan(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = ComplexNumber::createNan();
        $z = $x->subtract($y);

        self::assertTrue($z->isNan());

        $y = new ComplexNumber(1.0, NAN);
        $z = $x->subtract($y);

        self::assertTrue($z->isNan());
    }

    public function testSubtractInfinite(): void
    {
        $x = new ComplexNumber(1.0, 1.0);
        $y = new ComplexNumber(-INF, 0.0);
        $z = $x->subtract($y);

        self::assertSame(1.0, $z->getImaginary());
        self::assertSame(INF, $z->getReal());

        $x = new ComplexNumber(-INF, 0.0);
        $z = $x->subtract($y);

        self::assertNan($z->getReal());
    }

    public function testMultiply(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = new ComplexNumber(5.0, 6.0);
        $z = $x->multiply($y);

        self::assertSame(-9.0, $z->getReal());
        self::assertSame(38.0, $z->getImaginary());
    }

    public function testMultiplyNan(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = ComplexNumber::createNan();
        $z = $x->multiply($y);

        self::assertTrue($z->isNan());

        $x = ComplexNumber::createNan();
        $y = new ComplexNumber(5.0);
        $z = $x->multiply($y);

        self::assertTrue($z->isNan());
    }

    public function testMultiplyInfiniteInfinite(): void
    {
        $x = ComplexNumber::createInfinite();
        $y = ComplexNumber::createInfinite();
        $z = $x->multiply($y);

        self::assertTrue($z->isInfinite());
    }

    public function testMultiplyNanInfinite(): void
    {
        $x = new ComplexNumber(1.0, 1.0);
        $y = new ComplexNumber(INF, 1.0);
        $z = $x->multiply($y);

        self::assertSame(INF, $z->getReal());
        self::assertSame(INF, $z->getImaginary());

        $x = new ComplexNumber(1.0, 0.0);
        $z = $x->multiply($y);

        self::assertTrue($z->isInfinite());

        $x = new ComplexNumber(-1.0, 0.0);
        $z = $x->multiply($y);

        self::assertTrue($z->isInfinite());

        $x = new ComplexNumber(1.0, 0.0);
        $y = new ComplexNumber(-INF, 0.0);
        $z = $x->multiply($y);

        self::assertTrue($z->isInfinite());

        $x = new ComplexNumber(1.0, INF);
        $y = new ComplexNumber(1.0, -INF);
        $z = $x->multiply($y);

        self::assertSame(INF, $z->getReal());
        self::assertSame(INF, $z->getImaginary());

        $x = new ComplexNumber(-INF, -INF);
        $y = new ComplexNumber(1.0, NAN);
        $z = $x->multiply($y);

        self::assertNan($z->getReal());
        self::assertNan($z->getImaginary());

        $x = new ComplexNumber(1.0, -INF);
        $y = new ComplexNumber(-INF);
        $z = $x->multiply($y);

        self::assertTrue($z->isInfinite());
    }

    public function testDivide(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = new ComplexNumber(5.0, 6.0);
        $z = $x->divide($y);

        self::assertEquals(39.0 / 61.0, $z->getReal());
        self::assertEquals(2.0 / 61.0, $z->getImaginary());
    }

    public function testDivideReal(): void
    {
        $x = new ComplexNumber(2.0, 3.0);
        $y = new ComplexNumber(2.0, 0.0);
        $z = $x->divide($y);

        self::assertSame(1.0, $z->getReal());
        self::assertSame(1.5, $z->getImaginary());
    }

    public function testDivideImaginary(): void
    {
        $x = new ComplexNumber(2.0, 3.0);
        $y = new ComplexNumber(0.0, 2.0);
        $z = $x->divide($y);

        self::assertSame(1.5, $z->getReal());
        self::assertSame(-1.0, $z->getImaginary());
    }

    public function testDivideInfinite(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = new ComplexNumber(-INF, INF);
        $z = $x->divide($y);

        self::assertSame(0.0, $z->getReal());
        self::assertSame(0.0, $z->getImaginary());

        $z = $y->divide($x);

        self::assertNan($z->getReal());
        self::assertInfinite($z->getImaginary());

        $y = new ComplexNumber(INF, INF);
        $z = $y->divide($x);

        self::assertInfinite($z->getReal());
        self::assertNan($z->getImaginary());

        $x = new ComplexNumber(1.0, INF);
        $z = $y->divide($x);

        self::assertNan($z->getReal());
        self::assertNan($z->getImaginary());
    }

    public function testDivideZero(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = ComplexNumber::createZero();
        $z = $x->divide($y);

        self::assertTrue($z->isNan());
    }

    public function testDivideZeroZero(): void
    {
        $x = ComplexNumber::createZero();
        $y = ComplexNumber::createZero();
        $z = $x->divide($y);

        self::assertTrue($z->isNan());
    }

    public function testDivideNan(): void
    {
        $x = new ComplexNumber(3.0, 4.0);
        $y = ComplexNumber::createNan();
        $z = $x->divide($y);

        self::assertTrue($z->isNan());
    }

    public function testDivideNanInfinite(): void
    {
        $x = new ComplexNumber(1.0, INF);
        $y = new ComplexNumber(1.0);
        $z = $x->divide($y);

        self::assertNan($z->getReal());
        self::assertInfinite($z->getImaginary());

        $x = new ComplexNumber(-INF, -INF);
        $y = new ComplexNumber(1.0, NAN);
        $z = $x->divide($y);

        self::assertNan($z->getReal());
        self::assertNan($z->getImaginary());

        $x = new ComplexNumber(-INF, INF);
        $y = new ComplexNumber(1.0);
        $z = $x->divide($y);

        self::assertNan($z->getReal());
        self::assertNan($z->getImaginary());
    }
}
