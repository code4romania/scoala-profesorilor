<?php

namespace GinoPane\AwesomeSocialLinks\Models;

/**
 * Class LinkItem
 *
 * @package GinoPane\AwesomeSocialLinks\Models
 */
class LinkItem
{
    /**
     * @var string
     */
    private $icon = '';

    /**
     * @var string
     */
    private $link = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return LinkItem
     */
    public function setIcon(string $icon): LinkItem
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return LinkItem
     */
    public function setLink(string $link): LinkItem
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return LinkItem
     */
    public function setName(string $name): LinkItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param array $data
     *
     * @return LinkItem
     */
    public function fillFromArray(array $data): LinkItem
    {
        if (isset($data['icon'])) {
            $this->setIcon((string) $data['icon']);
        }

        if (isset($data['link'])) {
            $this->setLink((string) $data['link']);
        }

        if (isset($data['name'])) {
            $this->setName((string) $data['name']);
        }

        return $this;
    }
}