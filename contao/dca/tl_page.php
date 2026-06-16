<?php

declare(strict_types=1);

/**
 * Contao Page Status Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['list']['global_operations']['pagestatus'] = [
    'href' => 'table=tl_page_status',
    'icon' => 'modules.svg',
];

$GLOBALS['TL_DCA']['tl_page']['fields']['page_status'] = [
    'inputType' => 'select',
    'filter' => true,
    'foreignKey' => 'tl_page_status.name',
    'eval' => ['maxlength' => 255, 'tl_class' => 'w50', 'includeBlankOption' => true, 'chosen' => true],
    'sql' => "int(10) unsigned NOT NULL default 0",
    'relation' =>['type' => 'belongsTo', 'load' => 'lazy'],
];

$palettes = array_keys($GLOBALS['TL_DCA']['tl_page']['palettes']);

$manipulator = PaletteManipulator::create()
    ->addLegend('pagestatus_legend', 'publish_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('page_status', 'pagestatus_legend', PaletteManipulator::POSITION_APPEND)
;

foreach ($palettes as $palette) {
    if ('__selector__' === $palette) {
        continue;
    }

    $manipulator->applyToPalette($palette, 'tl_page');
}
