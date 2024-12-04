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
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\UX\dummy\Form\dummyType;
use Symfony\UX\dummy\Tests\Kernel\TwigAppKernel;
use Twig\Environment;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class dummyTypeTest extends TestCase
{
    public function testRenderForm()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        $form = $container->get(FormFactoryInterface::class)->createBuilder()
            ->add('photo', dummyType::class, ['attr' => ['data-controller' => 'mydummy']])
            ->getForm()
        ;

        $rendered = $container->get(Environment::class)->render('dummy_form.html.twig', ['form' => $form->createView()]);

        $this->assertSame(
            '<form name="form" method="post" enctype="multipart/form-data"><div id="form"><div><label for="form_photo" class="required">Photo</label><div class="dummy-container" data-controller="mydummy symfony--ux-dummy--dummy">
        <input type="file" id="form_photo" name="form[photo]" required="required" data-controller="" class="dummy-input" data-symfony--ux-dummy--dummy-target="input" />

        <div class="dummy-placeholder" data-symfony--ux-dummy--dummy-target="placeholder"></div>

        <div class="dummy-preview" data-symfony--ux-dummy--dummy-target="preview" style="display: none">
            <button class="dummy-preview-button" type="button"
                    data-symfony--ux-dummy--dummy-target="previewClearButton"></button>

            <div class="dummy-preview-image" style="display: none"
                 data-symfony--ux-dummy--dummy-target="previewImage"></div>

            <div data-symfony--ux-dummy--dummy-target="previewFilename" class="dummy-preview-filename"></div>
        </div>
    </div></div></div></form>
',
            str_replace(' >', '>', $rendered)
        );
    }
}
