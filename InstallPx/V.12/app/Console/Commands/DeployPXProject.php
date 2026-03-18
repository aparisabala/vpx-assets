<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use File;
class DeployPXProject extends Command
{
    private array $options = ['for', 'name', 'user', 'pass', 'smtp'];
    private array $muted = ['socket', 'baseUrl'];
    public function __construct()
    {
        parent::__construct();
    }

    
    /*
    * Command Configurations
    * @return void
    */
    protected function configure(): void
    {
        $commnd = $this->setName(name: 'px:deploy');
        foreach ([...$this->options, ...$this->muted] as $key => $value) {
            $commnd->addOption(name: $value, mode: InputOption::VALUE_OPTIONAL);
        }
    }
    

    /* Handle the command
    * @return void
    */
    public function handle(): void
    {
        //validate given command argument/options, if fails show error message
        if(count(array_diff_key(array_flip($this->options),$this->userOptions())) > 0) {
            $this->error("Can't skip required options: ".implode(",",array_map(function($item){return "--".$item;},$this->options)));
            exit;
        }
        $this->deploy();
    }

    /*
    * Deploy project either localhost or live environment
    * @return void
    */
    private function deploy(): void
    {
        try {
            $this->processEnv();
            $this->processJsConfig();
            $this->procssHtaccess();
        } catch (\Exception $e) {
            $this->error('Something went  wrong . Trace: ' . $e->getMessage());
        }
    }

      /*
    * Process .env file, edit and set env variables
    * @return void
    */
    private function processEnv(): void
    {

        $options = $this->userOptions();
        list($input, $output) = [$this->getAssetDir() . 'env.stub', base_path('.env')];
        $content = [
            "find" => ['{{for}}', '{{name}}', '{{user}}', '{{pass}}', '{{smtp}}'],
            "replace" => [($options['for'] == "local") ? "LOCAL" : "SERVER", $options['name'], $options['user'], $options['pass'], $this->getSmtpValues()]
        ];
        $this->updateStubs(input: $input, content: $content, output: $output);
        $this->info("File created: {$output}");
    }

    /*
    * Smtp setup for application
    * @return string
    */
    private function getSmtpValues(): string
    {
        $options = $this->userOptions();
        $emailOptions = explode("|", $options['smtp']);
        list($host, $email, $password) = ["parameter-x.com", "oriansoft@parameter-x.com", "b%kgCmM5eTx7"];
        if (count($emailOptions) == 3) {
            $host = $emailOptions[0];
            $email = $emailOptions[1];
            $password = $emailOptions[2];
        }
        $content = <<<PHP
        MAIL_MAILER=smtp
        MAIL_HOST={$host}
        MAIL_PORT=465
        MAIL_USERNAME={$email}
        MAIL_PASSWORD="{$password}"
        MAIL_ENCRYPTION=ssl
        MAIL_FROM_ADDRESS="{$email}"
        MAIL_FROM_NAME="\${APP_NAME}"
        PHP;
        return $content;
    }

    /*
    * Add base url support for ajax calling from extrenal scripts
    * @return void
    */
    private function processJsConfig(): void
    {
        $options = $this->userOptions();
        list($hasSocket, $socketServer) = (array_key_exists('socket', $options)) ? ((count(explode("|", $options['socket'])) == 2) ? explode("|", $options['socket']) : [false, "default"]) : [false, "default"];
        $socketConfig = $this->getSocketConfig(hasSocket: $hasSocket, socketServer: $socketServer);
        $options = $this->userOptions();
        list($input, $output) = [($options['for'] == "local") ? $this->getAssetDir() . 'localJs.stub' : $this->getAssetDir() . 'serverJs.stub', base_path('public/js/config/config.js')];
        $baseUrl = (isset($options['baseUrl'])) ? $options['baseUrl'] : 'http://localhost:8000';
        $content = [
            "find" => ['{{baseUrl}}', '{{socket}}'],
            "replace" => [$baseUrl, $socketConfig]
        ];
        $this->updateStubs(input: $input, content: $content, output: $output);
        $this->info("File created: {$output}");
    }

    /*
    * Socket config for locan and live server
    * @param string $hasSocket
    * @param string $socketServer
    * @return string
    */
    private function getSocketConfig($hasSocket, $socketServer): string
    {
        if ($hasSocket == null || !$hasSocket || $hasSocket !== "true") {
            return <<<PHP
            socket = false;
            PHP;
        }

        if ($hasSocket) {
            if ($socketServer == "default") {
                return <<<PHP
                socket = io.connect(domain+':3000');
                PHP;
            } else {
                return <<<PHP
                socket = io.connect('https://{$socketServer}');
                PHP;
            }
        }

        return <<<PHP
        socket = false;
        PHP;
    }

    /*
    * Add htaccess file to perform muti routes support
    * @return void
    */
    private function procssHtaccess(): void
    {
        ($this->userOptions() == "live") ? File::copy($this->getAssetDir() . '.htaccess', public_path('.htaccess')) : File::copy($this->getAssetDir() . '.htaccessLocal', public_path('.htaccess'));
        $this->info("File created: " . public_path('.htaccess'));
    }

    /**
     * Retunr asset directory string
     *
     * @return String
     */
    private function getAssetDir($append = ''): String
    {
        return resource_path('vpx/deploy/');
    }

    /*
    * Get and set stub contents
    * @param string $input
    * @param array $content
    * @param string $output
    * @retun void
    */
    private function updateStubs($input,$content,$output)
    {
        $stub = File::get($input);
        $content = str_replace(
            $content['find'],
            $content['replace'],
            $stub
        );
        File::put($output, $content);
    }

    /*
    * Store all the options given from commands
    * @return array
    */
    protected function userOptions(): array
    {
        $options = [];
        foreach ([...$this->options, ...$this->muted] as $key => $value) {
            if ($this->input->getParameterOption("--$value", false) !== false) {
                $options[(string)$value] = $this->option($value);
            }
        }
        return $options;
    }


}
