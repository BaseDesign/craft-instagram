<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram\models;

use basedesign\instagram\Instagram;

use Craft;
use craft\base\Model;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $accessToken = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'accessToken'], 'string'],
            [['username', 'accessToken'], 'required'],
        ];
    }
}
