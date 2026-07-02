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
use Doctrine\DBAL\Connection;
use Plenta\ContaoPageStatusBundle\Trait\StatusLabelTrait;

#[AsCallback(table: 'tl_page', target: 'list.label.label')]
#[AsCallback(table: 'tl_article', target: 'list.label.label')]
class TreeLabelCallbackListener
{
    use StatusLabelTrait;

    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(
        array $row,
        string $label,
        DataContainer $dc,
        string $imageAttribute = '',
        bool $returnImage = false,
        ?bool $isProtected = null
    ): string {
        return match ($dc->table) {
            'tl_page' => $this->getPageLabel($row, $label, $dc, $imageAttribute, $returnImage, $isProtected),
            'tl_article' => $this->getArticleLabel($row, $label),
            default => $label,
        };
    }
}
