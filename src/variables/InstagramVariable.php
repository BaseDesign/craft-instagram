<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram\variables;

use basedesign\instagram\Instagram;

use Craft;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class InstagramVariable
{
    // Public Methods
    // =========================================================================

    public function getUsername()
    {
        $username = Instagram::$plugin->settings->getUsername();

        return $username;
    }

    public function getUserUrl()
    {
        $url = Instagram::$plugin->settings->getUserUrl();

        return $url;
    }

    public function getAccessToken()
    {
        $accessToken = Instagram::$plugin->settings->getAccessToken();

        return $accessToken;
    }

    public function getMediaFromUser()
    {
        $recentMediaFromUser = Instagram::$plugin->media->getFromUser();

        return $recentMediaFromUser;
    }

    public function getMediaFromUrls($urls)
    {
        $mediaFromUrls = Instagram::$plugin->media->getFromUrls($urls);

        return $mediaFromUrls;
    }
}
