<?php
namespace Blog\CustomCache\Model\Cache\Type;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;

/**
 * Class CacheType
 * @package Blog\CustomCache\Model\Cache\Type
 */
class CacheType extends TagScope
{
    /**
     * Cache type id
     */
    const TYPE_IDENTIFIER = 'blog_customcache';

    /**
     * Used to limits the cleaning just to items tagged with this tag
     */
    const CACHE_TAG = 'BLOG_CUSTOMCACHE';

    /**
     * @param FrontendPool $cacheFrontendPool
     */
    public function __construct(FrontendPool $cacheFrontendPool)
    {
        parent::__construct(
            $cacheFrontendPool->get(self::TYPE_IDENTIFIER),
            self::CACHE_TAG
        );
    }
}
