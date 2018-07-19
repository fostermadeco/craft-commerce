<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license https://craftcms.github.io/license/
 */

namespace craft\commerce\migrations;

use craft\db\Migration;

/**
 * m180601_161904_fix_orderLanguage migration.
 */
class m180601_161904_fix_orderLanguage extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $lang = \Craft::$app->language;
        $sql = 'update {{%commerce_orders}} set [[orderLocale]] = "'.$lang.'" where [[orderLocale]] is null';
        $this->execute($sql);

        if ($this->db->getIsPgsql()) {
            // Manually construct the SQL for Postgres
            // (see https://github.com/yiisoft/yii2/issues/12077)
            $this->execute('alter table {{%commerce_orders}} alter column [[orderLocale]] type varchar(12), alter column [[orderLocale]] set not null');
        } else {
            $this->alterColumn('{{%commerce_orders}}', 'orderLocale', $this->string(12)->notNull());
        }

        $this->renameColumn('{{%commerce_orders}}', 'orderLocale', 'orderLanguage');

        $sql = 'update {{%commerce_orders}} set [[orderLanguage]] = TRIM([[orderLanguage]])';

        $this->execute($sql);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180601_161904_fix_orderLanguage cannot be reverted.\n";
        return false;
    }
}