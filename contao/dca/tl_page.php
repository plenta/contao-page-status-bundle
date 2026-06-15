<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['list']['global_operations']['pagestatus'] = [
    'href' => 'table=tl_page_status',
    'icon' => 'modules.svg',
];

$GLOBALS['TL_DCA']['tl_page']['fields']['page_status'] = [
    'inputType' => 'select',
    'filter' => true,
    'foreignKey' => 'tl_page_status.name',
    'eval' => ['maxlength' => 255, 'tl_class' => 'w50', 'includeBlankOption' => true],
    'sql' => "int(10) unsigned NOT NULL default 0",
    'relation' =>['type' => 'belongsTo', 'load' => 'lazy'],
];

PaletteManipulator::create()
    ->addLegend('pagestatus_legend', 'publish_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('page_status', 'pagestatus_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('regular', 'tl_page')
    ->applyToPalette('forward', 'tl_page')
    ->applyToPalette('redirect', 'tl_page')
    ->applyToPalette('error_401', 'tl_page')
    ->applyToPalette('error_403', 'tl_page')
    ->applyToPalette('error_404', 'tl_page')
    ->applyToPalette('error_503', 'tl_page')
;
