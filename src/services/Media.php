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
    public function getMediaFromUser()
    {
        $allMedia = [];
        $token = Instagram::$plugin->settings->getAccessToken();

        if (empty($token)) {
            return $allMedia;
        }
        
        $cache = Craft::$app->getCache();
        $cachedMedia = $cache->get('instagram-user');
        
        if ($cachedMedia !== false) {
            $allMedia = $cachedMedia;
            
            return $allMedia;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $endpoint = "https://graph.instagram.com/me/media?fields=id,caption,media_url,thumbnail_url,permalink,media_type&access_token=${token}";

            $response = $client->get($endpoint);
            $responseBody = \GuzzleHttp\json_decode($response->getBody());
            $responseData = $responseBody->data;

            foreach ($responseData as $data) {
                $media = $this->parseInstagramApi($data);
                $allMedia[] = $media;
            }
            
            $cache->set(
                'instagram-user',
                $allMedia,
                60*60*24*30 // Cache for 1 month
            );
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
    public function getMediaFromUrls($urls)
    {
        $allMedia = [];

        foreach ($urls as $url) {
            preg_match('/(?:.*)?(instagram\.com\/p\/([\w|-]*))(?:\/)?(?:.*)?/', $url, $matches);
            if (count($matches) < 2) {
                Craft::warning(
                    Craft::t(
                        'instagram',
                        'The Instagram URL isn\'t valid',
                        ['name' => 'Instagram']
                    ),
                    __METHOD__
                );

                continue;
            }

            $cache = Craft::$app->getCache();
            $cachedMedia = $cache->get('instagram-id-' . $matches[2]);
            
            if ($cachedMedia !== false) {
                $allMedia[] = $cachedMedia;
                
                continue;
            }

            try {
                $client = new \GuzzleHttp\Client();
                $endpoint = 'https://www.' . $matches[1] . '/media/?size=l';
                
                $response = $client->get($endpoint);
                
                if ($response->getStatusCode() == 200) {
                    $media = [
                        'image' => $endpoint,
                        'url' => 'https://www.' . $matches[1]
                    ];
                    $allMedia[] = $media;
                    
                    $cache->set(
                        'instagram-id-' . $matches[2],
                        $media,
                        60*60*24*30 // Cache for 1 month
                    );
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
    
    // Protected Methods
    // =========================================================================
    
    // Parse the JSON that is returned by the Instagram API
    protected function parseInstagramApi($data) {
        $media = [];
        
        $mediaUrl = $data->thumbnail_url ?? $data->media_url ?? null;
        $caption = $data->caption ?? null;
        $type = $data->media_type ?? null;
        $permalink = $data->permalink ?? null;
        
        if (empty($mediaUrl) || empty($permalink)) {
            return $media;
        }
        
        $media = [
            'image' => $mediaUrl,
            'url' => $permalink,
            'caption' => $caption,
            'type' => strtolower($type)
        ];
        
        return $media;
    }
}
