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

use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Plenta\ContaoPageStatusBundle\Trait\StatusLabelTrait;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_form', target: 'list.label.label')]
class ListLabelCallbackListener
{
    use StatusLabelTrait;

    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(array $row, string $label, DataContainer $dc, array $labels): array
    {
        $lastIndex = array_key_last($labels);

        if (null === $lastIndex) {
            return $labels;
        }

        $labels[$lastIndex] = $this->getLabelWithStatus($row, (string) $labels[$lastIndex]);

        return $labels;
    }
}
