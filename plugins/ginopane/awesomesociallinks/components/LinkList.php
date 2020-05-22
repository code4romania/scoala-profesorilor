<?php

namespace GinoPane\AwesomeSocialLinks\Components;

use Cms\Classes\ComponentBase;
use GinoPane\AwesomeSocialLinks\Models\LinkItem;
use GinoPane\AwesomeSocialLinks\Plugin;
use GinoPane\AwesomeSocialLinks\Models\Settings;

/**
 * Class LinkList
 *
 * @package GinoPane\AwesomeSocialLinks\Components
 */
class LinkList extends ComponentBase
{
    /**
     * Works like cache for the case when OctoberCMS calls LinkList::defineProperties several times
     *
     * @var array
     */
    private static $definedProperties = [];

    const NAME = 'linkList';

    /**
     * @var LinkItem[]
     */
    public $links = [];

    /**
     * @var string
     */
    public $linkTarget = Settings::LINK_TARGET_BLANK;

    /**
     * Component Registration
     *
     * @return  array
     */
    public function componentDetails()
    {
        return [
            'name'        => Plugin::LOCALIZATION_KEY . 'components.link_list.name',
            'description' => Plugin::LOCALIZATION_KEY . 'components.link_list.description'
        ];
    }

    /**
     * Component Properties
     *
     * @return  array
     */
    public function defineProperties(): array
    {
        if (!empty(self::$definedProperties)) {
            return self::$definedProperties;
        }

        /** @var Settings $settings */
        $settings = Settings::instance();

        $links = $settings->getLinks();

        $properties = [];

        foreach ($links as $link) {
            $name = $link->getName();

            $properties[$name] = [
                'title' => ucwords($name),
                'type'  => 'checkbox',
                'default' => false,
                'showExternalParam' => false
            ];
        }

        $properties['linkTarget'] = [
            'group'       => Plugin::LOCALIZATION_KEY . 'components.link_list.link_settings_group',
            'title'       => Plugin::LOCALIZATION_KEY . 'components.link_list.link_target_title',
            'description' => Plugin::LOCALIZATION_KEY . 'components.link_list.link_target_description',
            'type'        => 'dropdown',
            'default'     => Settings::LINK_TARGET_BLANK,
            'showExternalParam' => false
        ];

        return (self::$definedProperties = $properties);
    }

    /**
     * @return array
     */
    public function getLinkTargetOptions(): array
    {
        return Settings::instance()->getLinkTargets();
    }

    /**
     * Prepare and set variables
     *
     * @return void
     */
    public function onRun()
    {
        $this->fillLinks();

        $this->linkTarget = $this->property('linkTarget');
    }

    /**
     * Fill links from settings, filter by component config
     */
    protected function fillLinks()
    {
        /** @var LinkItem[] $links */
        $links = Settings::instance()->getLinks();

        foreach ($links as $index => $link) {
            if (!$this->property($link->getName())) {
                unset($links[$index]);
            }
        }

        $this->links = $links;
    }
}
