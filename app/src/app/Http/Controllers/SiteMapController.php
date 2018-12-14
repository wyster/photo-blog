<?php

namespace App\Http\Controllers;

use App\Services\SiteMap\Contracts\SiteMapBuilder;
use Illuminate\Contracts\Cache\Factory as CacheManager;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Routing\ResponseFactory;

/**
 * Class SiteMapController.
 *
 * @package App\Http\Controllers
 */
class SiteMapController extends Controller
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var SiteMapBuilder
     */
    private $siteMapBuilder;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * SiteMapController constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param SiteMapBuilder $siteMapBuilder
     * @param CacheManager $cacheManager
     * @param Config $config
     */
    public function __construct(ResponseFactory $responseFactory, SiteMapBuilder $siteMapBuilder, CacheManager $cacheManager, Config $config)
    {
        $this->responseFactory = $responseFactory;
        $this->siteMapBuilder = $siteMapBuilder;
        $this->cacheManager = $cacheManager;
        $this->config = $config;
    }

    /**
     * @return Response
     */
    public function xml()
    {
        $siteMap = $this->cacheManager
            ->tags(['siteMap', 'posts', 'photos', 'tags'])
            ->remember('siteMap', $this->config->get('cache.lifetime'), function () {
                return $this->siteMapBuilder->build();
            });

        return $this->responseFactory
            ->view('app.site-map.index', ['siteMap' => $siteMap])
            ->header('Content-Type', 'text/xml');
    }
}
