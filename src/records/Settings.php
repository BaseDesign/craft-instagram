<?php
/**
 * Instagram plugin for Craft CMS 3.x
 *
 * Instagram integration for Craft CMS
 *
 * @link      https://www.basedesign.com
 * @copyright Copyright (c) 2020 Base Design
 */

namespace basedesign\instagram\records;

use basedesign\instagram\Instagram;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Base Design
 * @package   Instagram
 * @since     1.0.0
 */
class Settings extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    public static function tableName()
    {
        return '{{%instagram_settings}}';
    }
}
