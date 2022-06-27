<?php

namespace App\Console\Commands\Crawler;

use Illuminate\Console\Command;
use Weidner\Goutte\GoutteFacade;
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
        $priceBox = [];
        foreach ($html->find('div.list-view li') as $link) {
            $href = $link->find('a',0)->href ?? '';
            $text = $link->find('.webpimg-container img' ,0)->alt ?? '';
            $title = $link->find('.Layout_right__2f2Ss h3' ,0)->plaintext ?? '';
            $area = $link->find('.Layout_right__2f2Ss span' ,0)->plaintext ?? '';
            $price = $link->find('.Layout_right__2f2Ss p' ,0)->plaintext ?? '';
            $priceBox[] = [
                'name' => $text,
                'link' => $href,
                'title' => $title,
                'area' => $area,
                'price' => $price,
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
