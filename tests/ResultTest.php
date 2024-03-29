<?php

declare(strict_types=1);

namespace Tests;

use DateTime;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use WillemVerspyck\SnowflakeService\Result;

/**
 * Class ResultTest
 */
class ResultTest extends TestCase
{
    /**
     * @var Result
     */
    private Result $result;

    /**
     *
     */
    public function setUp(): void
    {
        $this->result = new Result();
        $this->result->setId('ID');
        $this->result->setExecuted(false);
    }

    /**
     * Test getId
     */
    public function testGetId(): void
    {
        self::assertEquals('ID', $this->result->getId());
    }

    /**
     * Test getTotal
     */
    public function testGetTotal(): void
    {
        self::assertNull($this->result->getTotal());

        $this->result->setTotal(10);

        self::assertEquals(10, $this->result->getTotal());
    }

    /**
     * Test getPage
     */
    public function testGetPage(): void
    {
        self::assertNull($this->result->getPage());

        $this->result->setPage(1);

        self::assertEquals(1, $this->result->getPage());
    }

    /**
     * Test getPageTotal
     */
    public function testGetPageTotal(): void
    {
        self::assertNull($this->result->getPageTotal());

        $this->result->setPageTotal(20);

        self::assertEquals(20, $this->result->getPageTotal());
    }

    /**
     * Test getFields
     */
    public function testGetFields(): void
    {
        self::assertNull($this->result->getFields());

        $this->result->setFields([
            [
                'name' => 'Field1',
            ],
        ]);

        self::assertEquals([
            [
                'name' => 'Field1',
            ],
        ], $this->result->getFields());
    }

    /**
     * Test getData
     */
    public function testGetData(): void
    {
        self::assertNull($this->result->getData());

        $this->result->setFields([
            [
                'name' => 'FIELD1',
                'type' => 'text',
                'scale' => null,
            ],
            [
                'name' => 'FIELD2',
                'type' => 'boolean',
                'scale' => null,
            ],
        ]);

        $this->result->setData([
            [
                '0',
                'value1',
                '1',
            ],
            [
                '1',
                'value2',
                '0',
            ],
        ]);

        self::assertEquals([
            [
                'FIELD1' => 'value1',
                'FIELD2' => true,
            ],
            [
                'FIELD1' => 'value2',
                'FIELD2' => false,
            ],
        ], $this->result->getData());
    }

    /**
     * Test getDataRaw
     */
    public function testGetDataRaw(): void
    {
        self::assertNull($this->result->getDataRaw());

        $this->result->setData([
            [
                'field1',
                'field2',
            ],
            [
                'field1',
                'field2',
            ],
        ]);

        self::assertEquals([
            [
                'field1',
                'field2',
            ],
            [
                'field1',
                'field2',
            ],
        ], $this->result->getDataRaw());
    }

    /**
     * Test getTimestamp
     */
    public function testGetTimestamp(): void
    {
        self::assertNull($this->result->getTimestamp());

        $this->result->setTimestamp(1633082116654);

        self::assertInstanceOf(DateTimeInterface::class, $this->result->getTimestamp());
        self::assertEquals(new DateTime('2021-10-01T09:55:16.0000000+00:00'), $this->result->getTimestamp());
    }

    /**
     * Test isExecuted
     */
    public function testIsExecuted(): void
    {
        self::assertFalse($this->result->isExecuted());
    }
}
