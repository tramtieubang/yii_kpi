<?php
namespace app\common\helpers;

use Yii;

class MenuHelper
{
    /**
     * Kiểm tra menu active
     * @param string $moduleId
     * @param string|null $controllerId
     * @param string|array|null $actionIds
     * @return string ' active' nếu đúng, '' nếu không
     */
    public static function isActive($moduleId, $controllerId = null, $actionIds = null)
    {
        $currentModule = Yii::$app->controller->module->id;
        $currentController = Yii::$app->controller->id;
        $currentAction = Yii::$app->controller->action->id;

        if ($currentModule != $moduleId) {
            return '';
        }

        if ($controllerId !== null && $currentController != $controllerId) {
            return '';
        }

        if ($actionIds !== null) {
            if (is_array($actionIds)) {
                if (!in_array($currentAction, $actionIds)) {
                    return '';
                }
            } else {
                if ($currentAction != $actionIds) {
                    return '';
                }
            }
        }

        return ' active';
    }
}
