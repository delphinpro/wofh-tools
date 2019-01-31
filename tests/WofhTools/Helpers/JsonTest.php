<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace WofhTools\Helpers;


use PHPUnit\Framework\TestCase;


class JsonTest extends TestCase
{

    /** @var Json */
    private $json;


    public function setUp()
    {
        $this->json = new Json();
    }


    public function tearDown()
    {
        $this->json = null;
    }


    public function testEncode()
    {
        $data = [
            [
                'test'   => 'Кириллица',
                'url'    => '/api/check',
                'apos'   => 'api\'check',
                'quote'  => 'api"check"',
                'float'  => 1.0,
                'number' => '10',
            ],
        ];
        $this->assertEquals(
            $this->json->encode($data),
            '[{"test":"Кириллица","url":"/api/check","apos":"api\u0027check","quote":"api\u0022check\u0022","float":1.0,"number":10}]'
        );
        $this->assertEquals(
            $this->json->encode($data, true),
            '[
    {
        "test": "Кириллица",
        "url": "/api/check",
        "apos": "api\u0027check",
        "quote": "api\u0022check\u0022",
        "float": 1.0,
        "number": 10
    }
]'
        );
    }


    public function testDecode()
    {
        $this->assertArraySubset(
            $this->json->decode('[{"test": 12}]'),
            [['test' => 12]]
        );

        $this->expectException(JsonCustomException::class);
        $this->json->decode('');
    }
}
