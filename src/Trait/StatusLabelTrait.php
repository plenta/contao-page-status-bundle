<?php

declare(strict_types=1);

/**
 * Contao Page Status Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\ContaoPageStatusBundle\Trait;

use Contao\System;
use Contao\Backend;
use Contao\DataContainer;

trait StatusLabelTrait
{
    private function getPageLabel(
        array $row,
        string $label,
        DataContainer $dc,
        string $imageAttribute,
        bool $returnImage,
        ?bool $isProtected
    ): string {
        $coreLabel = Backend::addPageIcon($row, $label, $dc, $imageAttribute, $returnImage, $isProtected ?? false);

        return $coreLabel.$this->getStatus((int) $row['page_status']).$this->getPublishingStatus($row);
    }

    private function getArticleLabel(array $row, string $label): string
    {
        if (!isset($row['inColumn'])) {
            return Backend::addPageIcon($row, $label);
        }

        return System::importStatic('tl_article')->addIcon($row, $this->getLabelWithStatus($row, $label));
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
        $start = !empty($row['start']) ? (int) $row['start'] : null;
        $stop = !empty($row['stop']) ? (int) $row['stop'] : null;

        if (null === $start && null === $stop) {
            return '';
        }

        $parts = [];

        if (null !== $start) {
            $parts[] = $GLOBALS['TL_LANG']['MSC']['pagestatus']['start'].' '.date('d.m.Y', $start);
        }

        if (null !== $stop) {
            $parts[] = $GLOBALS['TL_LANG']['MSC']['pagestatus']['stop'].' '.date('d.m.Y', $stop);
        }

        return ' <span class="label-info">['.implode(' &ndash; ', $parts).']</span>';
    }

    private function getLabelWithStatus(array $row, string $label): string
    {
        return $label.$this->getStatus((int) ($row['page_status'] ?? 0)).$this->getPublishingStatus($row);
    }
}
