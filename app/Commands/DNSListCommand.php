<?php

namespace App\Commands;

use App\Api;
use GuzzleHttp\Exception\ClientException;
use LaravelZero\Framework\Commands\Command;

class DNSListCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dns:list {domain}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '获取域名解析列表';


    public function handle()
    {
        try {
            $domain = $this->argument('domain');
            $api = new Api();
            $dns = array_get($api->describeDomainRecords($domain), 'DomainRecords.Record');
            $dns = collect($dns)->map(function ($item) {
                return [
                    'recordId' => $item['RecordId'],
                    'dns' => sprintf('%s.%s', $item['RR'], $item['DomainName']),
                    'status' => $item['Status'],
                    'value' => $item['Value'],
                    'type' => $item['Type'],
                    'ttl' => $item['TTL']
                ];
            })->all();
            $this->table(['ID', 'DNS', 'Status', 'Value', 'Type', 'TTL'], $dns);
        } catch (ClientException $e) {
            $this->error($e->getMessage());
        }
    }
}
