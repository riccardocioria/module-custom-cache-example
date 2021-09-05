<?php declare(strict_types=1);

namespace Blog\CustomCache\Console\Command;

use Magento\Framework\HTTP\Client\Curl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\CacheInterface;


class TestCacheCommand extends Command
{
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        CacheInterface $cache,
        Curl $curl
    ) {
        parent::__construct('blog:test:cache');
        $this->curl = $curl;
        $this->cache = $cache;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('blog:test:cache');
        $this->setDescription('This is a test command.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getCachedData();
        if (!is_null($data)) {
            $output->writeln('<info>Cached Data loaded.</info>');
            return;
        }

        $data = $this->getDelayedResource();
        $this->storeCache($data);

        $output->writeln('<info>Original Data loaded and saved in cache.</info>');
    }

    private function getCachedData(): ?string
    {
        $cacheKey  = \Blog\CustomCache\Model\Cache\Type\CacheType::TYPE_IDENTIFIER;
        if ($this->cache->load($cacheKey)) {
            return $this->cache->load($cacheKey);
        }

        return null;
    }

    private function storeCache(string $data): void
    {
        $cacheKey  = \Blog\CustomCache\Model\Cache\Type\CacheType::TYPE_IDENTIFIER;
        $cacheTag  = \Blog\CustomCache\Model\Cache\Type\CacheType::CACHE_TAG;

        $this->cache->save(
            $data,
            $cacheKey,
            [$cacheTag],
            86400 // cached data lifetime
        );
    }

    private function getDelayedResource(): string
    {
        $url = 'http://deelay.me/5000/https://www.google.com';
        $this->curl->setOption(CURLOPT_FOLLOWLOCATION, true);
        $this->curl->get($url);
        return $this->curl->getBody();
    }
}
