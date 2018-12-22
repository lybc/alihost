<?php

namespace App\Commands;

use App\Api;
use App\ConfigHelper;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DomainListCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'domain:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '获取域名列表';

    use ConfigHelper;

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            $api = new Api();
            $domains = array_get($api->describeDomains(), 'Domains.Domain');
            foreach ($domains as &$domain) {
                unset($domain['DnsServers']);
            }
            $this->table(['Record Count', 'Puny Code', 'VersionCode', 'AliDomain', 'Domain Name', 'Domain Id', 'VersionName'], $domains);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
