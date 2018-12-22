<?php
namespace App;

use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Api
{
    use ConfigHelper;

    protected $http;

    public function __construct()
    {
        $this->readConfig();
        $this->http = new Client([
            'base_uri' => $this->config['url'],
            'timeout' => 30
        ]);
    }

    /**
     * 计算参数签名
     * @param array $params
     * @param string $requestMethod
     * @return string
     */
    public function makeSign(array $params, $requestMethod)
    {
        ksort($params);
        $paramString = "";
        $n = 0;
        foreach ($params as $k => $v) {
            //对参数名称和参数值进行 URL 编码
            $k = rawurlencode($k);
            $v = rawurlencode($v);
            //对编码后的参数名称和值使用英文等号（=）进行连接
            if ($n != 0) {
                $paramString .= "&";
            }
            $paramString .= $k . "=" . $v;
            $n++;
        }
        $stringToSign = $requestMethod . "&" . rawurlencode("/") . "&" . rawurlencode($paramString);
        return base64_encode(hash_hmac('sha1', $stringToSign, $this->config['secret'] . '&', true));
    }

    /**
     * 构造请求参数
     * @param array $queryParam
     * @param string $requestMethod
     * @return array
     */
    public function makeParams(array $queryParam, $requestMethod = 'GET')
    {
        $params = array_merge([
            'Format' => $this->config['format'],
            'Version' => $this->config['version'],
            'AccessKeyId' => $this->config['accessKey'],
            'SignatureMethod' => $this->config['signatureMethod'],
            'Timestamp' => date(DateTime::ISO8601),
            'SignatureNonce' => uniqid(),
            'SignatureVersion' => $this->config['signatureVersion']
        ], $queryParam);
        $params['Signature'] = $this->makeSign($params, $requestMethod);
        return $params;
    }

    /**
     * 获取域名列表
     * @param null $keyword
     * @param null $groupId
     * @param int $pageNumber
     * @param int $pageSize
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function describeDomains($keyword = null, $groupId = null, $pageNumber = 1, $pageSize = 50): array
    {
        $params = [
            'Action' => 'DescribeDomains',
            'PageNumber' => $pageNumber,
            'PageSize' => $pageSize,
        ];
        if (!empty($keyword)) {
            $params['KeyWord'] = $keyword;
        }

        if (!empty($groupId)) {
            $params['groupId'] = $groupId;
        }

        $response = $this->http->request('GET', '/', [
            'query' => $this->makeParams($params)
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * 获取域名解析列表
     * @param $domain
     * @param null $rrKeyWord
     * @param null $typeKeyWork
     * @param null $valueKeyWord
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function describeDomainRecords($domain, $rrKeyWord = null, $typeKeyWork = null, $valueKeyWord = null): array
    {
        $params = [
            'Action' => 'DescribeDomainRecords',
            'DomainName' => $domain,
        ];

        if (! empty($rrKeyWord)) {
            $params['RRKeyWord'] = $rrKeyWord;
        }

        if (! empty($typeKeyWork)) {
            $params['TypeKeyWord'] = $typeKeyWork;
        }

        if (! empty($valueKeyWord)) {
            $params['ValueKeyWord'] = $typeKeyWork;
        }
        $response = $this->http->request('GET', '/', [
            'query' => $this->makeParams($params)
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function updateDomainRecord($id, $value, $rr, $type, $ttl)
    {
        $params = [
            'Action' => 'UpdateDomainRecord',
            'RecordId' => $id,
            'Value' => $value,
            'RR' => $rr,
            'Type' => $type,
            'TTL' => $ttl
        ];
        $response = $this->http->request('GET', '/', [
            'query' => $this->makeParams($params)
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

}
