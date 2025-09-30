<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Service;

/**
 * Basically a clone of \Magento\MediaGallerySynchronization\Model\FetchMediaStorageFileBatches, but it allows to specify a directory to synchronize.
 */
class SyncMediaGallery
{
    public function __construct(
        protected \Magento\Framework\Filesystem $filesystem,
        protected \Magento\MediaGalleryApi\Api\IsPathExcludedInterface $isPathExcluded,
        protected \Magento\MediaGallerySynchronizationApi\Api\SynchronizeFilesInterface $synchronizer,
        protected \Psr\Log\LoggerInterface $log,
        protected array $fileExtensions,
        protected int $batchSize,
    ) {}

    public function execute(string $directory = \MageSuite\BrandManagement\Controller\Adminhtml\Brand\NewImage::BRANDS_MEDIA_PATH): void
    {
        foreach ($this->getBatches($directory) as $batch) {
            try {
                $this->synchronizer->execute($batch);
            } catch (\Exception $exception) {
                $this->log->critical($exception);
            }
        }
    }

    public function getBatches(string $directory): \Traversable
    {
        $i = 0;
        $batch = [];

        $path = \Magento\Framework\App\Filesystem\DirectoryList::MEDIA . DIRECTORY_SEPARATOR . $directory;
        $mediaDirectory = $this->filesystem->getDirectoryRead($path);
        $absolutePath = $mediaDirectory->getAbsolutePath();

        foreach ($mediaDirectory->readRecursively($absolutePath) as $file) {
            if (!$this->isApplicable($file)) {
                continue;
            }

            $batch[] = $file;

            if (++$i == $this->batchSize) {
                yield $batch;
                $i = 0;
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            yield $batch;
        }
    }

    protected function isApplicable(string $path): bool
    {
        try {
            return $path
                && !$this->isPathExcluded->execute($path)
                && preg_match('#\.(' . implode("|", $this->fileExtensions) . ')$# i', $path);
        } catch (\Exception $exception) {
            $this->log->critical($exception);

            return false;
        }
    }
}
