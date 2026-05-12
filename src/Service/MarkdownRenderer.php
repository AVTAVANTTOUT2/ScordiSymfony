<?php

declare(strict_types=1);

namespace App\Service;

use League\CommonMark\CommonMarkConverter;

class MarkdownRenderer
{
    private CommonMarkConverter $converter;

    public function __construct()
    {
        $this->converter = new CommonMarkConverter();
    }

    public function render(string $content): string
    {
        return (string) $this->converter->convert($content);
    }
}
