<?php

namespace Dbout\FooterTags;

/**
 * Class FooterTags
 *
 * @package Dbout\FooterTags
 *
 * @author      Dimitri BOUTEILLE <bonjour@dimitri-bouteille.fr>
 * @link        https://github.com/dimitriBouteille Github
 * @copyright   (c) 2019 Dimitri BOUTEILLE
 */
class FooterTags
{

    /**
     * @var array
     */
    private $order = ['variable', 'script', 'link'];

    /**
     * @var FooterTagsGroup[]
     */
    protected $tags = [];

    /**
     * @var string
     */
    protected $indentation = '    ';

    /**
     * Function script
     *
     * @param string $path
     * @param array $attr
     * @return FooterTags
     */
    public function script(string $path, array $attr = []): self
    {
        $this->add($this->createScript('', array_merge(['src' => $path], $attr)), 'link');
        return $this;
    }

    /**
     * Function inline
     *
     * @param string $script
     * @param array $attr
     * @return FooterTags
     */
    public function inline(string $script, array $attr = []): self
    {
        $this->add($this->createScript($script, $attr), 'script');
        return $this;
    }

    /**
     * Function variable
     *
     * @param string $variableName
     * @param $value
     * @return FooterTags
     */
    public function variable(string $variableName, $value): self
    {
        if(is_array($value)) {
            $value = json_encode($value);
        } else if(is_string($value)) {
            $value = sprintf("'%s'", $value);
        }

        $var = sprintf('var %s = %s;', $variableName, $value);
        $this->add($this->createScript($var), 'variable');

        return $this;
    }

    /**
     * Function createScript
     *
     * @param string $script
     * @param array $attr
     * @return string
     */
    private function createScript(string $script, array $attr = []): string
    {
        $default = [
            'type' => 'text/javascript',
        ];

        $attributes = $this->makeStrAttributes(array_merge($default, $attr));

        return sprintf('<script %s>%s</script>', $attributes, $script);
    }

    /**
     * Function makeStrAttributes
     * ie : attr="value" secondAttr="value"
     *
     * @param array $attributes
     * @return string
     */
    protected function makeStrAttributes(array $attributes): string {

        $html = [];

        foreach ($attributes as $key => $value) {

            $attr = sprintf('%s="%s"', $key, $value);

            if(!empty($attr) && !empty($value)) {
                $html[] = $attr;
            }
        }

        return implode(' ', $html);
    }

    /**
     * Function add
     *
     * @param string $tag
     * @param string $group
     * @return void
     */
    private function add(string $tag, string $group): void
    {
        if(!key_exists($group, $this->tags)) {
            $this->tags[$group] = new FooterTagsGroup();
        }

        $this->tags[$group]->add($tag);
    }

    /**
     * Function render
     *
     * @param array $groups
     * @return string|null
     */
    public function render(array $groups = []): ?string {

        $groups = count($groups) > 0 ? $groups : $this->order;
        $html = [];

        foreach ($groups as $group) {

            if(key_exists($group, $this->tags)) {
                $tags = $this->tags[$group];
                foreach ($tags->get() as $tag) {
                    $html[] = $tag;
                }
            }
        }

        $html = count($html) > 0 ? sprintf("%s%s\n", $this->indentation, implode("\n" . $this->indentation, $html)) : '';
        $html = preg_replace(sprintf('#^%s#', $this->indentation), '', $html, 1);

        return $html;
    }

}