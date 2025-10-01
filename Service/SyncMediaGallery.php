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

        $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $absolutePath = $mediaDirectory->getAbsolutePath() . DIRECTORY_SEPARATOR . $directory;

        foreach ($mediaDirectory->readRecursively($absolutePath) as $file) {
            $file = ltrim($file, DIRECTORY_SEPARATOR);

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
                && preg_match('#\.(' . implode("|", $this->fileExtensions) . ')$# i', $path)
                && !str_contains($path, '.thumbs');
        } catch (\Exception $exception) {
            $this->log->critical($exception);

            return false;
        }
    }
}
