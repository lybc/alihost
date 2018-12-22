<?php

namespace App\Commands;

use App\Api;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DNSUpdateCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dns:update 
        {domain : 域rr名名称} 
        {rr : 已存在的主机记录，如果要解析@.exmaple.com，主机记录要填写”@”，而不是空} 
        {value : 需要修改的主机记录值} 
        {--type= : 解析记录类型, etc: A, NS, MX, TXT, CNAME...}
        {--nrr= : 修改的新主机记录, 如果要解析@.exmaple.com，主机记录要填写”@”，而不是空}
        {--ttl=600 : 生存时间，默认为600秒}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '修改一个域名解析';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $domain = $this->argument('domain');
        $value = $this->argument('value');
        $rr = $this->argument('rr');
        $newrr = $this->option('nrr');
        $type = $this->option('type');
        $ttl = $this->option('ttl');
        try {
            $api = new Api();
            $dns = array_get($api->describeDomainRecords($domain, $rr), 'DomainRecords.Record');
            $record = collect($dns)->first(function ($item) use ($rr) {
                return $item['RR'] = $rr;
            });
            if (empty($record)) {
                throw new \InvalidArgumentException(sprintf('未找到对应的解析记录：%s.%s', $rr, $domain));
            }

            $api->updateDomainRecord(
                $record['RecordId'],
                $value,
                $newrr ?? $rr,
                $type ?? $record['Type'],
                $ttl ?? $record['TTL']
            );

            $this->info('修改成功！');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
