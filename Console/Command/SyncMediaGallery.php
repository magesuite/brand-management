<?php

declare(strict_types=1);

namespace MageSuite\BrandManagement\Console\Command;

class SyncMediaGallery extends \Symfony\Component\Console\Command\Command
{
    public function __construct(
        protected \MageSuite\BrandManagement\Service\SyncMediaGallery $syncMediaGallery,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('magesuite:brand:sync-media-gallery');
        $this->setDescription('Synchronize local media storage with `media_gallery_asset` table.');

        parent::configure();
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
    {
        $this->syncMediaGallery->execute();

        return self::SUCCESS;
    }
}
