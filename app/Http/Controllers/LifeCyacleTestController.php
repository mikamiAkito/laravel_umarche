<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCyacleTestController extends Controller
{
    public function showServiceProviderTest()
    {

        $encrypt = app()->make("encrypter");
        $password = $encrypt->encrypt("password");

        $sample = app("serviceProviderTest");

        dd($sample, $password, $encrypt->decrypt($password));

    }

    public function showServiceContainerTest()
    {
        app()->bind("lifeCycleTest", function () {
            return "ライフサイクル";
        });

        $test = app()->make("lifeCycleTest");

        //サービスコンテナapp()ありのパターン
        app()->bind("sample", Sample::class);
        $sample = app()->make("sample");
        $sample->run();

        dd($test, app());
    }
}

class Sample
{
    public $message;
    public function __construct(Message $message)
    {
        $this->message = $message;
    }
    public function run()
    {
        $this->message->send();
    }
}

class Message
{
    public function send()
    {
        echo("メッセージ表示");
    }
}
