<?php

namespace App\Service;

use RuntimeException;

class ReadNextUnprocessedLineService
{
    /**
     * @var resource|null
     */
    protected $fileHandle = null;

    /**
     * Reads the file starting from the last processed line pointer position.
     *
     * @return string|null The next unprocessed line or null if no more lines are available.
     */
    public function read(string $filePath, string $positionFilePath): ?string
    {
        $this->openFile($filePath);

        $currentPosition = file_exists($positionFilePath) ? (int) file_get_contents($positionFilePath) : 0;
        fseek($this->fileHandle, $currentPosition);

        $line = fgets($this->fileHandle);
        if ($line !== false) {
            $lineSize = strlen($line);
            $nextPosition = $lineSize + $currentPosition;
            file_put_contents($positionFilePath, $nextPosition);
        }
        $this->closeFile();
        return $line;
    }

    private function openFile(string $filePath): void
    {
        $this->fileHandle = fopen($filePath, 'r');
        if (! $this->fileHandle) {
            throw new RuntimeException('Could not open file "' . $filePath . '"');
        }
    }

    private function closeFile(): void
    {
        if ($this->fileHandle !== null) {
            fclose($this->fileHandle);
            $this->fileHandle = null;
        }
    }
}
