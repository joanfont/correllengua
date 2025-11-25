<?php

namespace App\Tests\Unit\Infrastructure\Symfony\Http\File;

use App\Application\Service\Calendar\Calendar;
use App\Application\Service\File\Filesystem;
use App\Domain\Model\File\File;
use App\Domain\Repository\File\FileRepository;
use App\Infrastructure\Symfony\Http\File\SymfonyFileUploader;
use App\Tests\TestCase;
use DateTimeImmutable;

use function explode;
use function file_get_contents;
use function pathinfo;

use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class SymfonyFileUploaderTest extends TestCase
{
    public function testUploadWritesFileAndAddsToRepository(): void
    {
        self::bootKernel();

        $fileInfo = self::asset('image.png');

        $uploaded = new SymfonyUploadedFile(
            $fileInfo->getRealPath(),
            $fileInfo->getFilename(),
            null,
            null,
            true,
        );

        $rootFs = $this->createMock(Filesystem::class);
        $uploadsFs = $this->createMock(Filesystem::class);
        $fileRepo = $this->createMock(FileRepository::class);
        $calendar = $this->createMock(Calendar::class);

        $contents = file_get_contents($fileInfo->getRealPath());

        $rootFs
            ->expects($this->once())
            ->method('read')
            ->with($fileInfo->getRealPath())
            ->willReturn($contents);

        $rootFs
            ->expects($this->once())
            ->method('delete')
            ->with($fileInfo->getRealPath());

        $calendar
            ->method('now')
            ->willReturn(new DateTimeImmutable('2025-11-23'));

        $uploadsFs
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->callback(function (string $path) use ($uploaded) {
                    // base path should be 2025/11/23
                    $parts = explode(DIRECTORY_SEPARATOR, $path);
                    static::assertCount(4, $parts); // Y m d filename
                    static::assertSame('2025', $parts[0]);
                    static::assertSame('11', $parts[1]);
                    static::assertSame('23', $parts[2]);
                    static::assertStringEndsWith('.png', $parts[3]);
                    // filename should contain the original client name
                    static::assertStringContainsString(pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME), $parts[3]);

                    return true;
                }),
                $contents,
            );

        $capturedPath = null;

        $fileRepo
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function (File $file) use (&$capturedPath) {
                static::assertStringEndsWith('.png', $file->name());
                $capturedPath = $file->path();

                return true;
            }));

        // Register mocks into the test container so the service is built with them
        self::set('app.filesystem.root', $rootFs);
        self::set('app.filesystem.uploads', $uploadsFs);
        self::set(FileRepository::class, $fileRepo);
        self::set(Calendar::class, $calendar);

        // Retrieve the uploader from the container so it uses the registered mocks
        $uploader = self::get(SymfonyFileUploader::class);

        $result = $uploader->upload($uploaded);

        static::assertInstanceOf(File::class, $result);
        static::assertSame($capturedPath, $result->path());
        static::assertStringEndsWith('.png', $result->name());
    }
}
