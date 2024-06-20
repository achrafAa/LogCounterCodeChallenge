<?php

declare(strict_types=1);

namespace App\Service;

use RuntimeException;

class ReadNextUnprocessedLineService
{
    /**
     * Reads the file starting from the last processed line pointer position.
     *
     * @return string|false The next unprocessed line or false if no more lines are available.
     */
    public function read(string $filePath, string $positionFilePath): string|false
    {
        $fileHandle = $this->openFile($filePath);

        $currentPosition = file_exists($positionFilePath) ? (int) file_get_contents($positionFilePath) : 0;
        fseek($fileHandle, $currentPosition);

        $line = fgets($fileHandle);
        if ($line !== false) {
            $lineSize = strlen($line);
            $nextPosition = $lineSize + $currentPosition;
            file_put_contents($positionFilePath, $nextPosition);
        }
        $this->closeFile($fileHandle);

        return $line;
    }

    /*** @return resource|false **/
    private function openFile(string $filePath)
    {
        if (! $fileHandle = fopen($filePath, 'r')) {
            throw new RuntimeException('Could not open file "' . $filePath . '"');
        }

        return $fileHandle;
    }

    private function closeFile($fileHandle): void
    {
        if ($fileHandle !== null) {
            fclose($fileHandle);
        }
    }
}
