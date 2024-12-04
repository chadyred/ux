Symfony UX dummy
===================

Symfony UX dummy is a Symfony bundle providing light dummys for
file inputs in Symfony Forms. It is part of `the Symfony UX initiative`_.

It allows visitors to drag and drop files into a container instead of
having to browse their computer for a file.

Installation
------------

.. caution::

    Before you start, make sure you have `StimulusBundle configured in your app`_.

Install the bundle using Composer and Symfony Flex:

.. code-block:: terminal

    $ composer require symfony/ux-dummy

If you're using WebpackEncore, install your assets and restart Encore (not
needed if you're using AssetMapper):

.. code-block:: terminal

    $ npm install --force
    $ npm run watch

Usage
-----

The most common usage of Symfony UX dummy is to use it as a drop-in
replacement of the native FileType class::

    // ...
    use Symfony\UX\dummy\Form\dummyType;

    class CommentFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                // ...
                ->add('photo', dummyType::class)
                // ...
            ;
        }

        // ...
    }

Customizing the design
~~~~~~~~~~~~~~~~~~~~~~

Symfony UX dummy provides a default stylesheet in order to ease
usage. You can disable it to add your own design if you wish.

In ``assets/controllers.json``, disable the default stylesheet by
switching the ``@symfony/ux-dummy/dist/style.min.css`` autoimport to
``false``:

.. code-block:: json

    {
        "controllers": {
            "@symfony/ux-dummy": {
                "dummy": {
                    "enabled": true,
                    "fetch": "eager",
                    "autoimport": {
                        "@symfony/ux-dummy/dist/style.min.css": false
                    }
                }
            }
        },
        "entrypoints": []
    }

.. note::

   *Note*: you should put the value to ``false`` and not remove the line
   so that Symfony Flex won’t try to add the line again in the future.

Once done, the default stylesheet won’t be used anymore and you can
implement your own CSS on top of the dummy.

Extend the default behavior
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Symfony UX dummy allows you to extend its default behavior using a
custom Stimulus controller:

.. code-block:: javascript

    // mydummy_controller.js

    import { Controller } from '@hotwired/stimulus';

    export default class extends Controller {
        connect() {
            this.element.addEventListener('dummy:connect', this._onConnect);
            this.element.addEventListener('dummy:change', this._onChange);
            this.element.addEventListener('dummy:clear', this._onClear);
        }

        disconnect() {
            // You should always remove listeners when the controller is disconnected to avoid side-effects
            this.element.removeEventListener('dummy:connect', this._onConnect);
            this.element.removeEventListener('dummy:change', this._onChange);
            this.element.removeEventListener('dummy:clear', this._onClear);
        }

        _onConnect(event) {
            // The dummy was just created
        }

        _onChange(event) {
            // The dummy just changed
        }

        _onClear(event) {
            // The dummy has just been cleared
        }
    }

Then in your form, add your controller as an HTML attribute::

    // ...
    use Symfony\UX\dummy\Form\dummyType;

    class CommentFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                // ...
                ->add('photo', dummyType::class, [
                    'attr' => ['data-controller' => 'mydummy'],
                ])
                // ...
            ;
        }

        // ...
    }

Backward Compatibility promise
------------------------------

This bundle aims at following the same Backward Compatibility promise as
the Symfony framework:
https://symfony.com/doc/current/contributing/code/bc.html

.. _`the Symfony UX initiative`: https://ux.symfony.com/
.. _StimulusBundle configured in your app: https://symfony.com/bundles/StimulusBundle/current/index.html
