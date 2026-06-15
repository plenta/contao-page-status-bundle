<?php

namespace Plenta\ContaoPageStatusBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_page', target: 'list.label.label')]
class PageLabelCallbackListener
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(array $row, string $label, DataContainer $dc, string $imageAttribute = '', bool $returnImage = false, bool|null $isProtected = null): string
    {
        $status = $this->connection
            ->createQueryBuilder()
            ->select('page_status')
            ->from('tl_page')
            ->where('id=:id')
            ->setParameter('id', $row['id'])
            ->fetchAssociative()
        ;

        return $row['title'].$this->getStatus((int) $status['page_status']).$this->getPublishingStatus($row);
    }

    public function getStatus(int $id): ?string
    {
        if (0 === $id || null === $id) {
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

        if (!empty($status['color'])) {
            return ' <span class="label-info" style="color: #'.$status['color'].'">['.$status['name'].']</span>';
        }

        return $status['name'];
    }

    public function getPublishingStatus(array $row): string
    {
        $start = $row['start'] !== '' ? (int) $row['start'] : null;
        $stop = $row['stop'] !== '' ? (int) $row['stop'] : null;

        if ($start === null && $stop === null) {
            return '';
        }

        $parts = [];

        if ($start !== null) {
            $parts[] = 'ab ' . date('d.m.Y', $start);
        }

        if ($stop !== null) {
            $parts[] = 'bis ' . date('d.m.Y', $stop);
        }

        return ' <span class="label-info">['.implode(' &ndash; ', $parts).']</span>';
    }
}
