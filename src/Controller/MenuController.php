<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\SimpleCache\CacheInterface;
use Studio24\Frontend\Cms\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends AbstractController
{
    /**
     * Frameworks Rest API data
     *
     * @var Wordpress
     */
    protected $api;

    public function __construct(CacheInterface $cache)
    {
        $this->api = new Wordpress(
            getenv('WEBCMS_ROOT_URL').'/wp-json/'
        );
        $this->api->setCache($cache);
        $this->api->setCacheLifetime(9000);
    }


    /**
     * Generic menu controller
     *
     * @param integer $id
     * @param string $templatePath
     * @param string $currentPath
     * @return Response
     */
    public function menu(int $id, string $templatePath = 'menus/default-menu.html.twig')
    {
        $id = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $menu = $this->api->getMenu($id);

        if (empty($menu)) {
            return new Response();
        }
        
       
        return $this->render($templatePath, [
            'menu' => $menu,
        ]);
    }
}
