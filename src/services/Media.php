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
            Craft::warning(
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

        foreach ($urls as $url) {
            preg_match('/(?:.*)?(instagram\.com\/p\/[\w|-]*)(?:\/)?(?:.*)?/', $url, $matches);
            if (count($matches) < 2) {
                Craft::warning(
                    Craft::t(
                        'instagram',
                        'The Instagram URL isn\'t valid',
                        ['name' => 'Instagram']
                    ),
                    __METHOD__
                );

                return $allMedia;
            }

            $url = 'https://www.' . $matches[1];
            $urlMedia = 'https://www.' . $matches[1] . '/media/?size=l';

            try {
                $client = new \GuzzleHttp\Client();
                $endpoint = $urlMedia;
                
                $response = $client->get($endpoint);

                if ($response->getStatusCode() == 200) {
                    $allMedia[] = [
                        'url' => $url,
                        'image' => $endpoint
                    ];
                }
            } catch (\Exception $e) {
                Craft::warning(
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
