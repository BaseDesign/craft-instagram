<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram\services;

use basedesign\instagram\Instagram;

use Craft;
use craft\base\Component;
use craft\helpers\UrlHelper;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class Media extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Get recent Instagram media from the user that we have the information for.
     *
     * @return array
     */
    public function getFromUser()
    {
        $allMedia = [];
        $token = Instagram::$plugin->settings->getAccessToken();

        if (empty($token)) {
            return $allMedia;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $endpoint = "https://graph.instagram.com/me/media?fields=id,caption,media_url,thumbnail_url,permalink&access_token=${token}";

            $response = $client->get($endpoint);
            $response = \GuzzleHttp\json_decode($response->getBody());
            $responseData = $response->data;

            foreach ($responseData as $media) {
                $allMedia[] = [
                    'image' => $media->thumbnail_url ?? $media->media_url,
                    'caption' => $media->caption,
                    'url' => $media->permalink
                ];
            }
        } catch(\Exception $e) {
            Craft::info(
                Craft::t(
                    'instagram',
                    'There was an error retrieving media from Instagram.',
                    ['name' => 'Instagram']
                ),
                __METHOD__
            );
        }

        return $allMedia;
    }

    /**
     * Get Instagram media
     *
     * @param array $urls
     *
     * @return array
     */
    public function getFromUrls($urls)
    {
        $allMedia = [];
        $client = new \GuzzleHttp\Client();

        foreach ($urls as $url) {
            preg_match('/https:\/\/www\.instagram\.com\/p\/[\w|-]*\/?/', $url, $matches);

            if (count($matches) == 0) {
                Craft::info(
                    Craft::t(
                        'instagram',
                        'The Instagram URL isn\'t valid',
                        ['name' => 'Instagram']
                    ),
                    __METHOD__
                );

                return $allMedia;
            }

            $url = $matches[0];
            if (substr($url, strlen($url) - 1, 1) != '/') {
                $url .= '/';
            }

            try {
                $endpoint = "${url}media/?size=l";
                $media = $client->get($endpoint);

                if ($media->getStatusCode() == 200) {
                    $allMedia[] = [
                        'image' => $endpoint,
                        'url' => $url
                    ];
                }
            } catch (\Exception $e) {
                Craft::info(
                    Craft::t(
                        'instagram',
                        'There was an error retrieving media from Instagram',
                        ['name' => 'Instagram']
                    ),
                    __METHOD__
                );
            }
        }

        return $allMedia;
    }
}
