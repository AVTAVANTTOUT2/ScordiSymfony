<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\MarkdownRenderer;
use PHPUnit\Framework\TestCase;

class MarkdownRendererTest extends TestCase
{
    public function testRenderMarkdown(): void
    {
        $renderer = new MarkdownRenderer();
        $html = $renderer->render('**hello**');

        self::assertStringContainsString('<strong>hello</strong>', $html);
    }
}
