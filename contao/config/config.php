<?php

declare(strict_types=1);

/**
 * Contao Page Status Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

use Plenta\ContaoPageStatusBundle\Widget\IconPickerWidget;

$GLOBALS['BE_MOD']['content']['page']['tables'][] = 'tl_page_status';
$GLOBALS['BE_MOD']['content']['form']['tables'][] = 'tl_page_status';
$GLOBALS['BE_MOD']['content']['article']['tables'][] = 'tl_page_status';

$GLOBALS['BE_FFL']['iconPicker'] = IconPickerWidget::class;

$GLOBALS['TL_CSS'][] = 'bundles/plentapagestatus/style.css';
