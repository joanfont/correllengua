<?php

namespace App\Infrastructure\Symfony\Http\File;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\File\Filesystem;
use App\Application\Service\File\FileUploader;
use App\Domain\Model\File\File;
use App\Domain\Model\File\FileId;
use App\Domain\Repository\File\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class SymfonyFileUploader implements FileUploader
{
    public function __construct(
        private readonly Filesystem $rootFilesystem,
        private readonly Filesystem $uploadsFilesystem,
        private readonly FileRepository $fileRepository,
        private readonly Calendar $calendar,
    ) {
    }

    public function upload(\SplFileInfo $file): File
    {
        if (!$file instanceof SymfonyUploadedFile) {
            throw new \UnexpectedValueException(sprintf('Expected instance of %s', SymfonyUploadedFile::class));
        }

        $fileContents = $this->rootFilesystem->read($file->getRealPath());
        $this->rootFilesystem->delete($file->getRealPath());

        $basePath = $this->calendar->now()->format('Y/m/d');
        $fileName = $this->buildName($file);
        $path = implode(DIRECTORY_SEPARATOR, [$basePath, $fileName]);
        $this->uploadsFilesystem->write($path, $fileContents);

        $file = new File(
            id: FileId::generate(),
            name: $fileName,
            path: $path,
        );

        $this->fileRepository->add($file);

        return $file;
    }

    private function buildName(SymfonyUploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        return sprintf('%s.%s', uniqid($fileName.'-'), $extension);
    }
}
