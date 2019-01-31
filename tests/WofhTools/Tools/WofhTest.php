<?php
/** @noinspection PhpUnhandledExceptionInspection */

namespace WofhTools\Tools;


use PHPUnit\Framework\TestCase;
use WofhTools\Helpers\Http;
use WofhTools\Helpers\Json;


class WofhTest extends TestCase
{
    /** @var Wofh */
    private $wofh;


    public function setUp()
    {
        $this->wofh = new Wofh(
            new Http(),
            new Json()
        );
    }


    public function tearDown()
    {
        $this->wofh = null;
    }


    public function testMakeWorldId()
    {
        $this->assertEquals($this->wofh->makeWorldId(1, 12), 10012);
        $this->assertEquals($this->wofh->makeWorldId(1, 12, 1), 11012);
    }


    public function testSignToId()
    {
        $this->assertEquals($this->wofh->signToId('ja12'), 0, 'Unknown language');
        $this->assertEquals($this->wofh->signToId('ru12a'), 0, 'Unknown type');
        $this->assertEquals($this->wofh->signToId('asd'), 0, 'Invalid string');

        $this->assertEquals($this->wofh->signToId('ru12'), 10012);
        $this->assertEquals($this->wofh->signToId('ru12s'), 11012);
        $this->assertEquals($this->wofh->signToId('ru12t'), 12012);
        $this->assertEquals($this->wofh->signToId('en1'), 20001);
        $this->assertEquals($this->wofh->signToId('de1'), 30001, 'Germany');
        $this->assertEquals($this->wofh->signToId('int7'), 40007);
        $this->assertEquals($this->wofh->signToId('int12'), 40012);
        $this->assertEquals($this->wofh->signToId('int12s'), 41012);
        $this->assertEquals($this->wofh->signToId('int12t'), 42012);
    }


    public function testIdToSign()
    {
        $this->assertEquals($this->wofh->idToSign(12), '', 'Invalid world id (small)');
        $this->assertEquals($this->wofh->idToSign(90001), '', 'Invalid world id (lang)');
        $this->assertEquals($this->wofh->idToSign(19001), '', 'Invalid world id (type)');

        $this->assertEquals($this->wofh->idToSign(10012), 'ru12');
        $this->assertEquals($this->wofh->idToSign(11012), 'ru12s');
        $this->assertEquals($this->wofh->idToSign(12012), 'ru12t');
        $this->assertEquals($this->wofh->idToSign(20001), 'en1');
        $this->assertEquals($this->wofh->idToSign(30001), 'de1');
        $this->assertEquals($this->wofh->idToSign(40007), 'int7');
        $this->assertEquals($this->wofh->idToSign(40012), 'int12');
        $this->assertEquals($this->wofh->idToSign(41012), 'int12s');
        $this->assertEquals($this->wofh->idToSign(42012), 'int12t');
    }


    public function testDomainToId()
    {
        $this->assertEquals($this->wofh->domainToId('http://ru23.waysofhistory.com'), 10023,
            'http');
        $this->assertEquals($this->wofh->domainToId('https://ru23.waysofhistory.com'), 10023,
            'https');
        $this->assertEquals($this->wofh->domainToId('ru23.waysofhistory.com'), 10023);
        $this->assertEquals($this->wofh->domainToId('ru1s.waysofhistory.com'), 11001);
        $this->assertEquals($this->wofh->domainToId('ru2t.waysofhistory.com'), 12002);
//        $this->assertEquals($this->wofh->domainToId('en1.waysofhistory.com'), 20001);
        $this->assertEquals($this->wofh->domainToId('int1.waysofhistory.com'), 40001);
        $this->assertEquals($this->wofh->domainToId('int12.waysofhistory.com'), 40012);
        $this->assertEquals($this->wofh->domainToId('int12s.waysofhistory.com'), 41012);
        $this->assertEquals($this->wofh->domainToId('int12t.waysofhistory.com'), 42012);

        $this->assertEquals($this->wofh->domainToId('w1.wofh.ru'), 0);
        $this->assertEquals($this->wofh->domainToId('w1.wofh.de'), 0);
    }


    public function testIdToDomain()
    {
        $this->assertEquals(
            $this->wofh->idToDomain(10023, true),
            'https://ru23.waysofhistory.com',
            'https'
        );
        $this->assertEquals($this->wofh->idToDomain(10023), 'ru23.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(11001), 'ru1s.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(12002), 'ru2t.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(20001), 'en1.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(40001), 'int1.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(41012), 'int12s.waysofhistory.com');
        $this->assertEquals($this->wofh->idToDomain(42012), 'int12t.waysofhistory.com');
    }


    public function testGetStatusLink()
    {
        $this->assertEquals($this->wofh->getStatusLink('ru'), $this->statusLink('ru'));
        $this->assertEquals($this->wofh->getStatusLink('en'), $this->statusLink('int'));
        $this->assertEquals($this->wofh->getStatusLink('de'), $this->statusLink('int'));
        $this->assertEquals($this->wofh->getStatusLink('int'), $this->statusLink('int'));

        $this->assertFalse($this->wofh->getStatusLink('ja'), 'Unknown language');
    }


    public function testGetAllStatusLinks()
    {
        $this->assertArraySubset(
            $this->wofh->getAllStatusLinks(),
            [
                $this->statusLink('ru'),
                $this->statusLink('int'),
            ]
        );
    }


    public function testLoadStatusOfWorlds()
    {
        $link = $this->wofh->getStatusLink('en');
        $data = $this->wofh->loadStatusOfWorlds([$link]);
        $this->assertNotEmpty($data);
    }


    private function statusLink($lang)
    {
        return 'https://ru.waysofhistory.com/aj_statistics?lang='.$lang;
    }
}
