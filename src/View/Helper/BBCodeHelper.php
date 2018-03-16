<?php
/**
 * This file is part of me-tools.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-tools
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */
namespace MeTools\View\Helper;

use Cake\View\Helper;
use MeTools\Utility\Youtube;

/**
 * BBCode Helper.
 *
 * This helper allows you to handle some BBCode.
 * The `parser()` method executes all parsers.
 */
class BBCodeHelper extends Helper
{
    /**
     * Helpers
     * @var array
     */
    public $helpers = ['Html' => ['className' => 'MeTools.Html']];

    /**
     * Pattern
     * @var array
     */
    protected $pattern = [
        'image' => '/\[img](.+?)\[\/img]/',
        'readmore' => '/(<p(>|.*?[^?]>))?\[read\-?more\s*\/?\s*\](<\/p>)?/',
        'url' => '/\[url=[\'"](.+?)[\'"]](.+?)\[\/url]/',
        'youtube' => '/\[youtube](.+?)\[\/youtube]/',
    ];

    /**
     * Executes all parsers
     * @param string $text Text
     * @return string
     */
    public function parser($text)
    {
        //Gets all current class methods, except for `parser()` and `remove()`
        $methods = getChildMethods(get_class(), ['parser', 'remove']);

        //Calls dynamically each method
        foreach ($methods as $method) {
            $text = call_user_func([$this, $method], $text);
        }

        return $text;
    }

    /**
     * Removes all BBCode
     * @param string $text Text
     * @return string
     * @uses $pattern
     */
    public function remove($text)
    {
        return trim(preg_replace($this->pattern, null, $text));
    }

    /**
     * Parses image code.
     * <code>
     * [img]mypic.gif[/img]
     * </code>
     * @param string $text Text
     * @return string
     * @uses $pattern
     */
    public function image($text)
    {
        return preg_replace_callback($this->pattern['image'], function ($matches) {
            return $this->Html->image($matches[1]);
        }, $text);
    }

    /**
     * Parses "read mode" code. Example:
     * <code>
     * [read-more /]
     * </code>
     * @param string $text Text
     * @return string
     * @uses $pattern
     */
    public function readMore($text)
    {
        return preg_replace($this->pattern['readmore'], '<!-- read-more -->', $text);
    }

    /**
     * Parses url code.
     * <code>
     * [url="http://example"]my link[/url]
     * </code>
     * @param string $text Text
     * @return string
     * @uses $pattern
     */
    public function url($text)
    {
        return preg_replace_callback($this->pattern['url'], function ($matches) {
            return $this->Html->link($matches[2], $matches[1]);
        }, $text);
    }

    /**
     * Parses Youtube code.
     * You can use video ID or video url.
     *
     * Examples:
     * <code>
     * [youtube]bL_CJKq9rIw[/youtube]
     * </code>
     *
     * <code>
     * [youtube]http://youtube.com/watch?v=bL_CJKq9rIw[/youtube]
     * </code>
     * @param string $text Text
     * @return string
     * @uses MeTools\Utility\Youtube::getId()
     * @uses MeTools\View\Helper\HtmlHelper::youtube()
     * @uses $pattern
     */
    public function youtube($text)
    {
        return preg_replace_callback($this->pattern['youtube'], function ($matches) {
            $id = isUrl($matches[1]) ? Youtube::getId($matches[1]) : $matches[1];

            return $this->Html->youtube($id);
        }, $text);
    }
}
