<?php

namespace App\Commands;

use App\ConfigHelper;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class InitCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '初始化ALIhost命令行配置';

    use ConfigHelper;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setConfig('accessKey', $this->ask('请输入阿里云APP ACCESS KEY'));
        $this->setConfig('secret', $this->ask('请输入阿里云Secret'));

        $this->writeConfig();

        $this->info('初始化配置成功！');
    }
}
