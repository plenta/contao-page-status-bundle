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
use Contao\DataContainer;
use Plenta\ContaoPageStatusBundle\Trait\StatusLabelTrait;

#[AsCallback(table: 'tl_page_status', target: 'list.label.label')]
class PageStatusListLabelCallbackListener
{
    use StatusLabelTrait;

    public function __invoke(array $row, string $label, DataContainer $dc, array $labels): array
    {
        $lastIndex = array_key_last($labels);

        if (null === $lastIndex) {
            return $labels;
        }

        $labels[$lastIndex] = $this->renderStatusLabel($row);

        return $labels;
    }
}
