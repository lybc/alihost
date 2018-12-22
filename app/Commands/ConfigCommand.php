<?php

namespace App\Commands;

use App\ConfigHelper;
use LaravelZero\Framework\Commands\Command;

class ConfigCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'config {key : 设置项} {value : 设置值} {--list : 设置配置完毕后打印出所有的配置项}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = '修改用户设置';

    use ConfigHelper;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        try {
            $this->readConfig();
            $this->setConfig($key, $value);
            $this->writeConfig();
            $this->info('修改配置成功');

            if ($this->option('list')) {
                $this->table(array_keys($this->config), [array_values($this->config)]);
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
