<?php

declare(strict_types=1);

namespace Tests;

use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use WillemVerspyck\SnowflakeService\Client;
use WillemVerspyck\SnowflakeService\Exception\ResultException;
use WillemVerspyck\SnowflakeService\Exception\TranslateException;
use WillemVerspyck\SnowflakeService\Translate;
use WillemVerspyck\SnowflakeService\Exception\ParameterException;

/**
 * Class TranslateTest
 */
class TranslateTest extends TestCase
{
    /**
     * @var Translate
     */
    private Translate $translate;

    /**
     *
     */
    public function setUp(): void
    {
        $this->translate = new Translate();
    }

    /**
     * Test setFields
     */
    public function testGetFields(): void
    {
        $fields = [
            [
                'name' => 'NAME',
                'type' => 'fixed',
                'scale' => 0,
            ],
        ];

        $this->translate->setFields($fields);

        self::assertEquals($fields, $this->translate->getFields());
    }

    /**
     * Test setFields
     */
    public function testSetFields(): void
    {
        self::expectException(TranslateException::class);

        $this->translate->setFields([
            [
                'scale' => 0,
            ]
        ]);
    }

    /**
     * Test getBoolean
     */
    public function testGetBoolean(): void
    {
        $reflection = new ReflectionClass($this->translate);

        $method = $reflection->getMethod('getBoolean');
        $method->setAccessible(true);

        self::assertFalse($method->invokeArgs($this->translate, ['0']));
        self::assertTrue($method->invokeArgs($this->translate, ['1']));
        self::assertNull($method->invokeArgs($this->translate, [null]));
        self::assertNull($method->invokeArgs($this->translate, ['2']));
    }

    /**
     * Test getDate
     */
    public function testGetDate(): void
    {
        $reflection = new ReflectionClass($this->translate);

        $method = $reflection->getMethod('getDate');
        $method->setAccessible(true);

        self::assertEquals(new DateTime('1970-01-01'), $method->invokeArgs($this->translate, ['0']));
        self::assertEquals(new DateTime('1970-01-02'), $method->invokeArgs($this->translate, ['1']));
        self::assertEquals(new DateTime('2019-04-14'), $method->invokeArgs($this->translate, ['18000']));

        self::assertNull($method->invokeArgs($this->translate, [null]));
    }

    /**
     * Test getFixed
     */
    public function testGetFixed(): void
    {
        $reflection = new ReflectionClass($this->translate);

        $method = $reflection->getMethod('getFixed');
        $method->setAccessible(true);

        self::assertEquals(12345, $method->invokeArgs($this->translate, ['12345', 0]));
        self::assertIsInt($method->invokeArgs($this->translate, ['12345', 0]));

        self::assertEquals(12345, $method->invokeArgs($this->translate, ['12345.1234567890', 0]));
        self::assertIsInt($method->invokeArgs($this->translate, ['12345.1234567890', 0]));

        self::assertEquals(12345.0, $method->invokeArgs($this->translate, ['12345', 6]));
        self::assertIsFloat($method->invokeArgs($this->translate, ['12345', 6]));

        self::assertEquals(12345.123456789, $method->invokeArgs($this->translate, ['12345.1234567890', 6]));
        self::assertIsFloat($method->invokeArgs($this->translate, ['12345.1234567890', 6]));

        self::assertNull($method->invokeArgs($this->translate, [null, 0]));
    }

    /**
     * Test getTime
     */
    public function testGetTime(): void
    {
        $reflection = new ReflectionClass($this->translate);

        $method = $reflection->getMethod('getTime');
        $method->setAccessible(true);

        self::assertEquals(new DateTime('2021-03-19T17:06:59+00:00'), $method->invokeArgs($this->translate, ['1616173619.000000000']));
        self::assertEquals(new DateTime('2021-03-19T17:06:59.123456+00:00'), $method->invokeArgs($this->translate, ['1616173619.123456789']));

        self::assertNull($method->invokeArgs($this->translate, ['1616173619000000000']));
        self::assertNull($method->invokeArgs($this->translate, [null]));
    }

    /**
     * Test getTimeWithTimezone
     */
    public function testGetTimeWithTimezone(): void
    {
        $reflection = new ReflectionClass($this->translate);

        $method = $reflection->getMethod('getTimeWithTimezone');
        $method->setAccessible(true);

        self::assertEquals(new DateTime('2021-03-19T17:06:59+00:00'), $method->invokeArgs($this->translate, ['1616173619.000000000 0']));
        self::assertEquals(new DateTime('2021-03-19T17:36:59+00:30'), $method->invokeArgs($this->translate, ['1616173619.000000000 30']));
        self::assertEquals(new DateTime('2021-03-19T18:06:59+01:00'), $method->invokeArgs($this->translate, ['1616173619.000000000 60']));
        self::assertEquals(new DateTime('2021-03-20T09:06:59+16:00'), $method->invokeArgs($this->translate, ['1616173619.000000000 960']));
        self::assertEquals(new DateTime('2021-03-20T09:36:59+16:30'), $method->invokeArgs($this->translate, ['1616173619.000000000 990']));
        self::assertEquals(new DateTime('2021-03-19T17:06:59.123456+00:00'), $method->invokeArgs($this->translate, ['1616173619.123456789 0']));
        self::assertEquals(new DateTime('2021-03-19T17:36:59.123456+00:30'), $method->invokeArgs($this->translate, ['1616173619.123456789 30']));
        self::assertEquals(new DateTime('2021-03-19T18:06:59.123456+01:00'), $method->invokeArgs($this->translate, ['1616173619.123456789 60']));
        self::assertEquals(new DateTime('2021-03-20T09:06:59.123456+16:00'), $method->invokeArgs($this->translate, ['1616173619.123456789 960']));
        self::assertEquals(new DateTime('2021-03-20T09:36:59.123456+16:30'), $method->invokeArgs($this->translate, ['1616173619.123456789 990']));

        self::assertNull($method->invokeArgs($this->translate, ['1616173619.000000000']));
        self::assertNull($method->invokeArgs($this->translate, ['1616173619.123456789']));
        self::assertNull($method->invokeArgs($this->translate, ['1616173619000000000']));
        self::assertNull($method->invokeArgs($this->translate, [null]));
    }
}
