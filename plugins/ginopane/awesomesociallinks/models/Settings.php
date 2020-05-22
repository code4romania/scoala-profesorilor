<?php

namespace GinoPane\AwesomeSocialLinks\Models;

use GinoPane\AwesomeSocialLinks\Plugin;
use Model;
use October\Rain\Database\Traits\Validation;
use System\Behaviors\SettingsModel;

/**
 * Class Settings
 *
 * @package GinoPane\AwesomeSocialLinks\Models
 */
class Settings extends Model {
    use Validation;

    const SETTINGS_CODE = 'ginopane_awesomesociallinks';

    const LINK_TARGET_BLANK = '_blank';
    const LINK_TARGET_SELF = '_self';
    const LINK_TARGET_PARENT = '_parent';
    const LINK_TARGET_TOP = '_top';

    public $implement = [SettingsModel::class];

    public $settingsCode = self::SETTINGS_CODE;

    public $settingsFields = 'fields.yaml';

    public $rules = [
        'links.*.name'  => 'required',
        'links.*.link'   => 'required|url'
    ];

    public $customMessages = [
        'links.*.name.required' => Plugin::LOCALIZATION_KEY . 'validation.name_required',
        'links.*.link.required'  => Plugin::LOCALIZATION_KEY . 'validation.url_required',
        'links.*.link.url'  => Plugin::LOCALIZATION_KEY . 'validation.url_must_be_valid'
    ];

    /**
     * @return LinkItem[]
     */
    public function getLinks(): array
    {
        $links = [];

        if (!empty($this->links) && \is_array($this->links)) {
            foreach ($this->links as $link) {
                $links[] = (new LinkItem())->fillFromArray((array) $link);
            }
        }

        return $links;
    }

    /**
     * @return array
     */
    public function getLinkTargets(): array
    {
        return [
            self::LINK_TARGET_BLANK => self::LINK_TARGET_BLANK,
            self::LINK_TARGET_PARENT => self::LINK_TARGET_PARENT,
            self::LINK_TARGET_SELF => self::LINK_TARGET_SELF,
            self::LINK_TARGET_TOP => self::LINK_TARGET_TOP
        ];
    }
}