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

use Contao\Backend;
use Contao\DataContainer;
use Contao\System;
use Doctrine\DBAL\Connection;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_page', target: 'list.label.label')]
#[AsCallback(table: 'tl_article', target: 'list.label.label')]
class TreeLabelCallbackListener
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(array $row, string $label, DataContainer $dc, string $imageAttribute = '', bool $returnImage = false, bool|null $isProtected = null): string
    {
        return match ($dc->table) {
            'tl_page' => $this->getPageLabel($row, $label, $dc, $imageAttribute, $returnImage, $isProtected),
            'tl_article' => $this->getArticleLabel($row, $label),
            default => $label,
        };
    }

    private function getPageLabel(array $row, string $label, DataContainer $dc, string $imageAttribute, bool $returnImage, bool|null $isProtected): string
    {
        $coreLabel = Backend::addPageIcon($row, $label, $dc, $imageAttribute, $returnImage, $isProtected ?? false);

        return $coreLabel.$this->getStatus((int) $row['page_status']).$this->getPublishingStatus($row);
    }
    
    private function getArticleLabel(array $row, string $label): string
    {
        if (!isset($row['inColumn'])) {
            return Backend::addPageIcon($row, $label);
        }

        $labelWithStatus = $label.$this->getStatus((int) ($row['page_status'] ?? 0)).$this->getPublishingStatus($row);

        return System::importStatic('tl_article')->addIcon($row, $labelWithStatus);
    }

    private function getStatus(int $id): string
    {
        if (0 === $id) {
            return '';
        }

        $status = $this->connection
            ->createQueryBuilder()
            ->select('name', 'color')
            ->from('tl_page_status')
            ->where('id=:id')
            ->setParameter('id', $id)
            ->fetchAssociative()
        ;

        if (!$status) {
            return '';
        }

        if (!empty($status['color'])) {
            return ' <span class="label-info" style="color: #'.$status['color'].'">['.$status['name'].']</span>';
        }

        return ' <span class="label-info">['.$status['name'].']</span>';
    }

    private function getPublishingStatus(array $row): string
    {
        $start = $row['start'] !== '' ? (int) $row['start'] : null;
        $stop = $row['stop'] !== '' ? (int) $row['stop'] : null;

        if ($start === null && $stop === null) {
            return '';
        }

        $parts = [];

        if ($start !== null) {
            $parts[] = $GLOBALS['TL_LANG']['MSC']['pagestatus']['start'].' '.date('d.m.Y', $start);
        }

        if ($stop !== null) {
            $parts[] = $GLOBALS['TL_LANG']['MSC']['pagestatus']['stop'].' '.date('d.m.Y', $stop);
        }

        return ' <span class="label-info">['.implode(' &ndash; ', $parts).']</span>';
    }
}
