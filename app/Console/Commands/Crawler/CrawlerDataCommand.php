<?php

namespace App\Console\Commands\Crawler;

use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;
ini_set('user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36');
class CrawlerDataCommand extends Command
{
    protected $signature = 'crawler:init';

    protected $description = 'Crawler';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->init();
    }

    public function init()
    {
        $this->crawlerHouseLink();
        $this->crawlerHouse();

    }

    public function crawlerHouseLink()
    {
        $linkProduct = "https://nha.chotot.com/thue-bat-dong-san";
        $html = file_get_html($linkProduct);
        foreach ($html->find('#__next > div > div.container.ct-listing > div.styles_listViewWrapper__162PR > div.styles_base__2D2wo > main > div.no-padding.col-md-8.ct-col-8 > div.list-view > div > div:nth-child(1) > ul:nth-child(1) > div:nth-child(1) > li > a') as $link) {
            $href = $link->getAttribute('href') ?? '';
            $text = $link->plaintext ?? '';
            $priceBox[] = [
                'name' => $text,
                'link' => $href,
            ];
        }
        dd($priceBox);

    }

    public function crawlerHouse()
    {
        $linkProduct = "https://nha.chotot.com/thue-bat-dong-san";
        $html = file_get_html($linkProduct);
        foreach ($html->find('#__next > div > div.container.ct-listing > div.styles_listViewWrapper__162PR > div.styles_base__2D2wo > main > div.no-padding.col-md-8.ct-col-8 > div.list-view > div > div:nth-child(1) > ul:nth-child(1) > div:nth-child(1) > li > a > div.Layout_right__2f2Ss > div > h3') as $link) {
            $title = $link->plaintext ?? '';
            $priceBox[] = [
                'title' => $title,
            ];
        }
    }
}
