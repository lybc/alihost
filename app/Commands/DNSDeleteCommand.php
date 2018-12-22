<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DNSDeleteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dns:del';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '删除一条域名解析';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->error('暂未实现');
    }
}
