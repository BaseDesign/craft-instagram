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
use basedesign\instagram\records\Settings as SettingsRecord;

use Craft;
use craft\base\Component;
use craft\helpers\UrlHelper;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class Settings extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Get the Instagram username.
     *
     * @return string
     */
    public function getUsername()
    {
        $settings = Instagram::$plugin->getSettings();
        $username = $settings->username;

        return $username;
    }

    /**
     * Get the Instagram user URL.
     *
     * @return string
     */
    public function getUserUrl()
    {
        $username = $this->getUsername();
        $url = "https://www.instagram.com/${username}";

        return $url;
    }

    /**
     * Get the Instagram API access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        $record = $this->getRecord();
        $accessToken = $record->accessToken ?? null;

        if (empty($accessToken)) {
            $record = $this->renewToken($record);
        }

        $dateCurrent = new \DateTime;
        $dateCurrent = $dateCurrent->format('Y-m-d');
        $dateExpire = $record->dateExpire ?? null;

        if ($dateCurrent > $dateExpire) {
            $record = $this->renewToken($record);
        }

        $accessToken = $record->accessToken ?? null;

        return $accessToken;
    }

    /**
     * Get the information saved in the database.
     *
     * @return SettingsRecord
     */
    public function getRecord()
    {
        $record = new SettingsRecord;
        $recordFound = $record->find()->one();

        if (!empty($recordFound)) {
            $record = $recordFound;
        }

        return $record;
    }

    /**
     * Renew the Instagram API access token.
     *
     * @param SettingsRecord $record
     *
     * @return SettingsRecord
     */
    public function renewToken($record)
    {
        $accessToken = $record->accessToken ?? null;
        $dateExpire = $record->dateExpire ?? null;

        if (empty($accessToken)) {
            $settings = Instagram::$plugin->getSettings();
            $accessToken = $settings->accessToken;
        }

        try {
            $client = new \GuzzleHttp\Client();
            $endpoint = "https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=${accessToken}";

            $response = $client->get($endpoint);
            $response = \GuzzleHttp\json_decode($response->getBody());
            $responseAccessToken = $response->access_token ?? null;
            $responseExpiresIn = $response->expires_in ?? null;

            if (empty($responseAccessToken) or empty($responseExpiresIn)) {
                throw new \Exception;
            }

            $dateExpire = new \DateTime;
            $dateExpire->modify('+ ' . $responseExpiresIn . 'seconds');

            $record = $this->saveRecord($responseAccessToken, $dateExpire);

            return $record;
        } catch(\Exception $e) {
            Craft::warning(
                Craft::t(
                    'instagram',
                    'A new access token could not be retrieved.',
                    ['name' => 'Instagram']
                ),
                __METHOD__
            );
        
            return $record;
        }
    }

    /**
     * Save a record with the information in the database.
     *
     * @param string $accessToken
     * @param \DateTime $dateExpire
     *
     * @return SettingsRecord
     */
    public function saveRecord($accessToken = null, $dateExpire = null)
    {
        $settings = Instagram::$plugin->getSettings();
        $username = $settings->username;
        $accessToken = $accessToken ?? $settings->accessToken;
        $dateExpire = $dateExpire ?? '';

        $record = new SettingsRecord;
        $recordExisting = $record->find()->one();

        if (!empty($recordExisting)) {
            $record = $recordExisting;
        }

        $record->accessToken = $accessToken;
        $record->dateExpire = $dateExpire->format('Y-m-d H:i:s');

        if (!empty($accessToken)) {
            Craft::$app->getPlugins()->savePluginSettings(Instagram::$plugin, array(
                "accessToken" => $accessToken
            ));
        }

        if (!empty($recordExisting)) {
            $record->update();
        } else {
            $record->save();
        }

        return $record;
    }
}
