<?php

use Plenta\ContaoPageStatusBundle\Widget\IconPickerWidget;

$GLOBALS['BE_MOD']['content']['page']['tables'][] = 'tl_page_status';
$GLOBALS['BE_MOD']['content']['form']['tables'][] = 'tl_page_status';
$GLOBALS['BE_MOD']['content']['article']['tables'][] = 'tl_page_status';

$GLOBALS['BE_FFL']['iconPicker'] = IconPickerWidget::class;

$GLOBALS['TL_CSS'][] = 'bundles/plentapagestatus/style.css';