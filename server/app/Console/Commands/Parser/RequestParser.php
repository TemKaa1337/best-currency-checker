<?php

namespace App\Console\Commands\Parser;

use Illuminate\Support\Facades\Http;
use App\Services\Traits\Parser\Page;
use Illuminate\Console\Command;

class RequestParser extends Command
{
    use Page;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $type = 'request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parseMainPageWithRequest();

        return 0;
    }

    private function parseMainPageWithRequest(): void
    {
        $response = Http::get('https://myfin.by/currency/minsk');
        $this->getDataFromMainPage($response->body());
    }

    private function parseInnerPageWithRequest(string $url): array
    {
        sleep(1);

        while (True) {
            $response = Http::get($url);
            if ($response->status() === 200) break;

            echo "Got status code: {$response->status()}, waiting 5 seconds...";
            sleep(5);
        }

        return $this->getDataFromInnerPage($response->body());
    }
}
