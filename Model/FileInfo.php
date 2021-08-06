<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Message\ManagerInterface;

class FileInfo
{
    public const ENTITY_MEDIA_PATH = 'adeoweb/weatherconditions';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        Filesystem $filesystem,
        Mime $mime,
        ManagerInterface $messageManager
    ) {
        $this->filesystem = $filesystem;
        $this->mime = $mime;
        $this->messageManager = $messageManager;
    }

    private function getMediaDirectory(): WriteInterface
    {
        if ($this->mediaDirectory) {
            return $this->mediaDirectory;
        }

        try {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        } catch (FileSystemException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->mediaDirectory;
    }

    public function getMimeType(string $fileName): string
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        $absoluteFilePath = $this->getMediaDirectory()->getAbsolutePath($filePath);

        return $this->mime->getMimeType($absoluteFilePath);
    }

    public function getStat(string $fileName): array
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');

        return $this->getMediaDirectory()->stat($filePath);
    }

    public function isExist(string $fileName, $baseTmpPath = false): bool
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        return $this->getMediaDirectory()->isExist($filePath);
    }

    public function deleteFile(string $fileName, $baseTmpPath = false): bool
    {
        $filePath = self::ENTITY_MEDIA_PATH . '/' . ltrim($fileName, '/');
        if ($baseTmpPath) {
            $filePath = $baseTmpPath . '/' . ltrim($fileName, '/');
        }

        try {
            $this->getMediaDirectory()->delete($filePath);
        } catch(FileSystemException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());

            return false;
        }

        return true;
    }
}
