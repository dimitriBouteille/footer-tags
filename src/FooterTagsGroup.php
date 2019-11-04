<?php

namespace Dbout\FooterTags;

/**
 * Class FooterTagsGroup
 *
 * @package Dbout\FooterTags
 *
 * @author      Dimitri BOUTEILLE <bonjour@dimitri-bouteille.fr>
 * @link        https://github.com/dimitriBouteille Github
 * @copyright   (c) 2019 Dimitri BOUTEILLE
 */
class FooterTagsGroup
{

    /**
     * @var string[]
     */
    private $meta = [];

    /**
     * Function add
     *
     * @param string $meta
     * @return FooterTagsGroup
     */
    public function add(string $meta): self
    {
        $this->meta[] = $meta;
        return $this;
    }

    /**
     * Function get
     *
     * @return array
     */
    public function get(): array
    {
        return $this->meta;
    }

}