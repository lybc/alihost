<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class DNSAddCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dns:add';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '新增一条域名解析';

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
