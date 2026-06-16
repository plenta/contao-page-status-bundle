<?php

declare(strict_types=1);

/**
 * Contao Page Status Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\ContaoPageStatusBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsCallback(table: 'tl_page_status', target: 'config.onload')]
class PageStatusListener
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ('article' === $request?->query->get('do')) {
            $GLOBALS['TL_DCA']['tl_page_status']['config']['backlink'] = 'do=article';
        }
    }
}
