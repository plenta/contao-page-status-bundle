<?php

use Contao\DC_Table;
use Contao\DataContainer;

$GLOBALS['TL_DCA']['tl_page_status'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'tstamp' => 'index'
            ],
        ],
        'backlink' => 'do=page'
    ],

    'list' => [
        'sorting' => [
            'mode' => DataContainer::SORT_INITIAL_LETTER_ASC,
            'fields' => ['name'],
            'disableGrouping' => true,
        ],
        'label' => [
            'fields' => ['name'],
            'format' => '%s'
        ],
    ],

    'palettes' => [
        'default' => '{title_legend},name,color',
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default 0"
        ],
        'name' => [
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''"
        ],
        'color' => [
            'inputType'=> 'text',
            'eval'=> [
                'maxlength' => 6,
                'colorpicker' => true,
                'isHexColor' => true,
                'decodeEntities' => true,
                'tl_class' => 'w50 wizard'
            ],
            'sql'=> "varchar(6) COLLATE ascii_bin NOT NULL default ''"
        ],
    ],
];