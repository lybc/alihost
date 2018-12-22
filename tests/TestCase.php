<?php

namespace Tests;

use App\Api;
use function GuzzleHttp\Psr7\parse_query;
use LaravelZero\Framework\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function testMakeSign()
    {
        $api = new Api();
        $testUrl = 'http://alidns.aliyuncs.com/?Format=XML&AccessKeyId=testid&Action=DescribeDomainRecords&SignatureMethod=HMAC-SHA1&DomainName=example.com&SignatureNonce=f59ed6a9-83fc-473b-9cc6-99c95df3856e&SignatureVersion=1.0&Version=2015-01-09&Timestamp=2016-03-24T16:41:54Z';
        $urlSchema = parse_url($testUrl);
        $params = parse_query($urlSchema['query']);
        config(['api.secret' => 'testsecret']);
        $result = $api->makeSign($params, 'GET');
        $this->assertEquals($result, 'uRpHwaSEt3J+6KQD//svCh/x+pI=');
    }
}
