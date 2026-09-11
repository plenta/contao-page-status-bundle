<?php

declare(strict_types=1);

namespace Plenta\ContaoPageStatusBundle\Widget;

use Contao\Image;
use Contao\RadioButton;
use Contao\StringUtil;

class IconPickerWidget extends RadioButton
{
    public function generate(): string
    {
        $GLOBALS['TL_CSS'][] = 'bundles/plentapagestatus/css/icon-picker.css';

        $options = $this->arrOptions;

        // Nicht mehr konfigurierte Werte anzeigen, damit sie beim Speichern nicht verloren gehen
        if (isset($this->unknownOption[0])) {
            $options[] = ['value' => $this->unknownOption[0], 'label' => $this->unknownOption[0]];
        }

        $items = [];

        foreach ($options as $option) {
            $value = (string) ($option['value'] ?? '');
            $label = (string) ($option['label'] ?? $value);

            $items[] = \sprintf(
                '<label class="icon-picker__item" title="%s"><input type="radio" name="%s" value="%s"%s%s>%s</label>',
                StringUtil::specialchars($label),
                $this->strName,
                self::specialcharsValue($value),
                $this->isChecked($option),
                $this->getAttributes(),
                '' === $value ? '<span>&times;</span>' : file_get_contents(\dirname(__DIR__, 2).'/public/icons/'.$value.'.svg'),
            );
        }

        return \sprintf(
            '<fieldset id="ctrl_%s" class="icon-picker%s"><legend>%s%s%s%s</legend><div class="icon-picker__grid">%s</div></fieldset>%s',
            $this->strId,
            $this->strClass ? ' '.$this->strClass : '',
            $this->mandatory ? '<span class="invisible">'.$GLOBALS['TL_LANG']['MSC']['mandatory'].' </span>' : '',
            $this->strLabel,
            $this->mandatory ? '<span class="mandatory">*</span>' : '',
            $this->xlabel,
            implode('', $items),
            $this->wizard,
        );
    }
}