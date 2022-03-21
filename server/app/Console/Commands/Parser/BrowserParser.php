<?php

namespace App\Console\Commands\Parser;

use HeadlessChromium\BrowserFactory;
use App\Services\Traits\Parser\Page;
use Illuminate\Console\Command;

class BrowserParser extends Command
{
    use Page;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:browser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected string $type = 'browser';
    protected array $cities = ['minsk', 'brest', 'vitebsk', 'gomel', 'mogilev', 'grodno'];
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
        $this->parseMainPageWithBrowser();

        return 0;
    }

    private function parseMainPageWithBrowser(): void
    {
        $browserFactory = new BrowserFactory();

        // starts headless chrome
        $this->browser = $browserFactory->createBrowser([
            'headers' => [
                ':authority' => 'myfin.by',
                ':method' => 'POST',
                ':path' => '/currency/minsk',
                ':scheme' => 'https',
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'accept-encoding' => 'gzip, deflate, br',
                'accept-language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'cache-control' => 'max-age=0',
                'content-length' => '3407',
                'content-type' => 'application/x-www-form-urlencoded',
                'cookie' => 'PHPSESSID=8s792snv9cqi3takpn5lqt3rc4; _ym_uid=1646767858171861499; _ym_d=1646767858; _ym_isad=2; _ym_visorc=b; _csrf=12ee7f5ebb38e50ccc21e81ec4165e1a54b9a3fc1eb1be80a199b50369cb198ea%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22sVCCwiYBiW86AWmWTac2j4t84I97N_Xk%22%3B%7D; cf_clearance=ll4Ny44hsU2V0mDABuJOvCymUspoaG7.mFyxSZTUvSk-1646767877-0-150; __cf_bm=.yImDbcfbAlksmcASY6pLorJmIGJj3O4sXdm_0vqEFc-1646767878-0-AZqBuJBnHmji329H5z9rYomUkjEH6b7y2+fgBMMyv2TrAfQnVBAvSdKEhv6zslD/irGVBT35acU3cSqKidf80OTbn9mFTMukLeQnKRvSxFs4dssW4bsXnTIDZ+m3nsfZmg==; push_close=3; __gads=ID=eb33789a8a078916-225dbde456cd00b5:T=1646767895:RT=1646767895:S=ALNI_MZxKiCTt_zrwb6n9962SYM3INZvMA; cf_chl_rc_ni=5; cf_chl_2=e50836e83575d5d; cf_chl_prog=x11',
                'origin' => 'https://myfin.by',
                'sec-ch-ua' => '" Not A;Brand";v="99", "Chromium";v="98", "Opera";v="84"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => "Windows",
                'sec-fetch-dest' => 'document',
                'sec-fetch-mode' => 'navigate',
                'sec-fetch-site' => 'same-origin',
                'upgrade-insecure-requests' => '1',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.109 Safari/537.36 OPR/84.0.4316.31'
            ],
            'headless' => false,
            'windowSize' => [1920, 1000]
        ]);

        try {
            foreach ($this->cities as $city) {
                // TODO: add header
                $page = $this->browser->createPage();
                $page->navigate("https://myfin.by/currency/{$city}")->waitForNavigation();
                $html = $page->getHtml(5000);

                $this->getDataFromMainPage($html, $city);
            }
        } finally {
            $this->browser->close();
        }
    }

    private function parseInnerPageWithBrowser(string $url): array
    {
        try {
            // creates a new page and navigate to an URL
            $page = $this->browser->createPage();
            $page->navigate($url)->waitForNavigation();
            $html = $page->getHtml(5000);

            return $this->getDataFromInnerPage($html);
        } finally {

        }
    }
}
