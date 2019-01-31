<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace WofhTools\Helpers;


use PHPUnit\Framework\TestCase;


class HttpTest extends TestCase
{
    public function testReadUrl()
    {
        $http = new Http();
        try {

            $html = $http->readUrl('https://example.com');

        } catch (HttpCustomException $e) {

            $this->markTestSkipped($e->getMessage());

        }

        $a = array_filter(array_map('rtrim', explode("\n", $html)), 'trim');
        $html = join("\n", $a);

        $this->assertStringEqualsFile(__DIR__.'/../../assets/example.com.txt', $html);
    }


    public function testReadUrlFailed()
    {
        $http = new Http();
        $this->expectException(HttpCustomException::class);
        $http->readUrl('');
    }
}
