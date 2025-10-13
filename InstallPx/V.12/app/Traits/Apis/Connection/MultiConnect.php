<?php
namespace App\Traits\Apis\Connection;
use Config;
use DB;
trait MultiConnect
{
    public function MultiConnect($data,$name)
    {
        if(env('SERVER_MODE') == "LOCAL") {

            $this->localConnect($data,$name);

        } else {

            $this->serverConnect($data,$name);
        }
    }

    public function localConnect($data,$name)
    {
        Config::set('database.connections.institute.host',"localhost");
        Config::set('database.connections.institute.username', "root");
        Config::set('database.connections.institute.password', "");
        Config::set('database.connections.institute.database',$data['service_db']);
        DB::reconnect($name);
    }

    public function serverConnect($data,$name)
    {
        Config::set('database.connections.institute.host', "localhost");
        Config::set('database.connections.institute.username', $data['service_db_user']);
        Config::set('database.connections.institute.password', $data['service_db_password']);
        Config::set('database.connections.institute.database', $data['service_db']);
        DB::reconnect($name);
    }
}
