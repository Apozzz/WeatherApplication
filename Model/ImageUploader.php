<?php

declare(strict_types=1);

namespace AdeoWeb\WeatherConditions\Model;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader
{
    private const RESULT_FILE    = 'file';
    private const RESULT_URL     = 'url';
    private const RESULT_NAME    = 'name';
    private const RESULT_TMPNAME = 'tmp_name';
    private const RESULT_PATH    = 'path';
    private const TYPE_TMP       = 'tmp';
    private const TYPE_DIR       = 'dir';

    /**
     * @var Database
     */
    private $coreFileStorageDatabase;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $baseTmpPath;

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var string
     */
    private $allowedExtensions;

    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        ManagerInterface $messageManager,
        FileInfo $fileInfo,
        string $baseTmpPath = 'adeoweb/tmp/weatherconditions',
        string $basePath = 'adeoweb/weatherconditions',
        array $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png']
    ) {
        $this->messageManager = $messageManager;
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->fileInfo = $fileInfo;
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->baseTmpPath = $baseTmpPath;
        $this->basePath = $basePath;
        $this->allowedExtensions = $allowedExtensions;
        $this->getMediaDirectory($filesystem);
    }

    public function setBaseTmpPath(string $baseTmpPath): void
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }

    public function setAllowedExtensions(array $allowedExtensions): void
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    public function getBaseTmpPath(): string
    {
        return $this->baseTmpPath;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    public function getFilePath(string $path, string $imageName): string
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * @throws LocalizedException
     */
    public function moveFileFromTmp(string $imageName): string
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $basePath = $this->getBasePath();

        $baseImagePath = $this->getFilePath($basePath, $imageName);
        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);

        try {
            if ($this->getFileInfo()->isExist($imageName, $this->baseTmpPath)) {
                $this->coreFileStorageDatabase->copyFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
                $this->mediaDirectory->renameFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
            }
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $imageName;
    }

    private function getFileInfo(): FileInfo
    {
        return $this->fileInfo;
    }

    /**
     * @throws LocalizedException
     * @throws Exception
     */
    public function saveFileToTmpDir(string $fileId): array
    {
        $baseTmpPath = $this->getBaseTmpPath();

        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);

        $result = $this->saveResultInDestinationFolder($uploader, $baseTmpPath);

        unset($result[self::RESULT_PATH]);

        if (!$result) {
            throw new LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }

        $result = $this->setResult($result, $baseTmpPath);
        if (!isset($result[self::RESULT_FILE])) {
            return $result;
        }

        $this->saveResultFileInDBStorage($result, $baseTmpPath);

        return $result;
    }

    public function deleteImage(string $imageName, string $type = self::TYPE_DIR): void
    {
        $basePath = $this->getBasePath();
        if ($type === self::TYPE_TMP) {
            $basePath = $this->getBaseTmpPath();
        }

        if ($this->getFileInfo()->isExist($imageName, $basePath)) {
            $this->getFileInfo()->deleteFile($imageName, $basePath);
        }
    }

    private function getMediaDirectory(Filesystem $filesystem): void
    {
        try {
            $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        } catch (FileSystemException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }

    /**
     * @throws NoSuchEntityException
     */
    private function setResult(array $result, string $baseTmpPath): array
    {
        /**
         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
         */
        $result[self::RESULT_TMPNAME] = str_replace('\\', '/', $result[self::RESULT_TMPNAME]);
        $result[self::RESULT_URL] = $this->storeManager
                ->getStore()
                ->getBaseUrl(
                    UrlInterface::URL_TYPE_MEDIA
                ) . $this->getFilePath($baseTmpPath, $result[self::RESULT_FILE]);
        $result[self::RESULT_NAME] = $result[self::RESULT_FILE];

        return $result;
    }

    /**
     * @throws LocalizedException
     */
    private function saveResultFileInDBStorage(array $result, string $baseTmpPath): void
    {
        try {
            $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result[self::RESULT_FILE], '/');
            $this->coreFileStorageDatabase->saveFile($relativePath);
        } catch (Exception $e) {
            $this->logger->critical($e);
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }
    }

    /**
     * @throws LocalizedException
     */
    private function saveResultInDestinationFolder(Uploader $uploader, string $baseTmpPath): array
    {
        try {
            $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        } catch (Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while saving the file(s).')
            );
        }

        return $result;
    }
}
