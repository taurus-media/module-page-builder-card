<?php
/**
 * Copyright (c) 2024. Taurus. All rights reserved
 */
declare(strict_types=1);

namespace Taurus\PageBuilderCard\Plugin;

use Magento\Cms\Model\Template\Filter;

/**
 * Fills alt of Card images with the card title when alt is missing or empty
 */
class FillCardImageAlt
{
    /**
     * Use the card title as alt for card images that have no alt text
     *
     * @param Filter $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterFilter(Filter $subject, $result)
    {
        if (!is_string($result) || strpos($result, 'data-content-type="card"') === false) {
            return $result;
        }

        // From the card marker to its title: images always come first and no other content type can be in between
        $processed = preg_replace_callback(
            '#data-content-type="card"(?:(?!data-content-type=).)*?<span[^>]*data-element="title"[^>]*>(.*?)</span>#s',
            function (array $card): string {
                $title = html_entity_decode(strip_tags(preg_replace('#<br\s*/?>#i', ' ', $card[1])), ENT_QUOTES | ENT_HTML5);
                $title = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $title));
                if ($title === '') {
                    return $card[0];
                }

                $alt = ' alt="' . htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE) . '"';

                return preg_replace_callback(
                    '#<img\b[^>]*data-element="(?:desktop|mobile)_image"[^>]*>#',
                    function (array $img) use ($alt): string {
                        $tag = preg_replace_callback('#\salt=(["\'])\s*\1#', fn() => $alt, $img[0], 1, $count);
                        if ($count === 0 && !preg_match('#\salt=#', $tag)) {
                            $tag = '<img' . $alt . substr($tag, 4);
                        }

                        return $tag;
                    },
                    $card[0]
                );
            },
            $result
        );

        return $processed ?? $result;
    }
}
