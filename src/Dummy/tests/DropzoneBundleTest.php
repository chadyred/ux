<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\dummy\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\UX\dummy\Tests\Kernel\EmptyAppKernel;
use Symfony\UX\dummy\Tests\Kernel\FrameworkAppKernel;
use Symfony\UX\dummy\Tests\Kernel\TwigAppKernel;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class dummyBundleTest extends TestCase
{
    public static function provideKernels()
    {
        yield 'empty' => [new EmptyAppKernel('test', true)];
        yield 'framework' => [new FrameworkAppKernel('test', true)];
        yield 'twig' => [new TwigAppKernel('test', true)];
    }

    /**
     * @dataProvider provideKernels
     */
    public function testBootKernel(Kernel $kernel)
    {
        $kernel->boot();
        $this->assertArrayHasKey('dummyBundle', $kernel->getBundles());
    }

    public function testFormThemeMerging()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $this->assertEquals([
            'form_div_layout.html.twig',
            '@dummy/form_theme.html.twig',
            'form_theme.html.twig',
        ], $kernel->getContainer()->getParameter('twig.form.resources'));
    }
}
