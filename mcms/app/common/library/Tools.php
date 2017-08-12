<?php

namespace Mcms\Library;

use Phalcon\Mvc\User\Plugin;

class Tools extends Plugin
{
    /**
     * Converts an MySQL formatted date to French date
     * @param string $mysqlDate
     * @param bool $includeHours
     * @return string
     */
    public static function mysqlDateToFr($mysqlDate, $includeHours = true)
    {
        if ($mysqlDate == null) {
            return "-";
        }
        $date = date("d/m/Y", strtotime($mysqlDate));
        if ($date == false) {
            return "-";
        }
        if ($includeHours) {
            $date .= " à " . date("H\\hi", strtotime($mysqlDate));
        }
        return $date;
    }

    /**
     * Returns the current date in MySQL format DateTime
     * @return string
     */
    public static function now()
    {
        return date("Y-m-d H:i:s");
    }

    /**
     * Truncate HTML, close opened tags
     * @param int $maxLength of the string
     * @param string $html
     * @param bool $printElipsis
     * @return string
     * @link https://stackoverflow.com/a/8228785/7057668
     */
    public static function truncateHTML($maxLength, $html, $printElipsis = true)
    {
        mb_internal_encoding("UTF-8");

        $printedLength = 0;
        $position = 0;
        $tags = array();

        ob_start();

        while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position)) {
            list($tag, $tagPosition) = $match[0];

            // Print text leading up to the tag.
            $str = mb_strcut($html, $position, $tagPosition - $position);
            if ($printedLength + mb_strlen($str) > $maxLength) {
                print(mb_strcut($str, 0, $maxLength - $printedLength));
                $printedLength = $maxLength;
                break;
            }
            print($str);
            $printedLength += mb_strlen($str);
            if ($tag[0] == '&') {
                // Handle the entity.
                print($tag);
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/') {
                    // This is a closing tag.
                    $openingTag = array_pop($tags);
                    assert($openingTag == $tagName); // check that tags are properly nested.
                    print($tag);
                } else if ($tag[mb_strlen($tag) - 2] == '/') {
                    // Self-closing tag.
                    print($tag);
                } else {
                    // Opening tag.
                    print($tag);
                    $tags[] = $tagName;
                }
            }
            // Continue after the tag.
            $position = $tagPosition + mb_strlen($tag);
        }

        // Print any remaining text.
        if ($printedLength < $maxLength && $position < mb_strlen($html))
            print(mb_strcut($html, $position, $maxLength - $printedLength));

        if ($printElipsis && strlen($html) > $maxLength) {
            print('...');
        }

        // Close any open tags.
        while (!empty($tags))
            printf('</%s>', array_pop($tags));

        $bufferOuput = ob_get_contents();
        ob_end_clean();
        $html = $bufferOuput;

        return $html;
    }
}
