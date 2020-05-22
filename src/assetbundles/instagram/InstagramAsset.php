<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram\assetbundles\instagram;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class InstagramAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@basedesign/instagram/assetbundles/instagram/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Instagram.js',
        ];

        $this->css = [
            'css/Instagram.css',
        ];

        parent::init();
    }
}
