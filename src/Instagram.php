<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram;

use basedesign\instagram\models\Settings as SettingsModel;
use basedesign\instagram\services\Settings as SettingsService;
use basedesign\instagram\services\Media as MediaService;;
use basedesign\instagram\variables\InstagramVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class Instagram
 *
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 *
 * @property  MediaService $mediaService
 * @property  SettingsService $settingsService
 */
class Instagram extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Instagram
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public $hasCpSettings = true;

    /**
     * @var bool
     */
    public $hasCpSection = false;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->_registerComponents();

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('instagram', InstagramVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'instagram',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }



    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        $settingsModel = new SettingsModel();

        return $settingsModel;
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'instagram/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }

    // Private Methods
    // =========================================================================

    /**
     * Registers the components
     */
    private function _registerComponents()
    {
        $this->setComponents([
            'settings' => SettingsService::class,
            'media' => MediaService::class,
        ]);
    }

}
