<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 26.04.17
 * Time: 19:48
 */

namespace app\modules\teacher\helpers;

use yii\db\Query;
use Yii;

class Profile
{

    public static function userInfo(){

        $queryUser = (new Query())
            ->select(['u.id as user_id', 'u.first_name', 'u.last_name', 'u.email', 'u_instr.instricon_id as instr_id', '`instr`.icon', '`instr`.instr_name'])
            ->from('user u')
            ->leftJoin('userinstr u_instr', 'u.id = u_instr.user_id')
            ->leftJoin('instricon `instr`', 'u_instr.instricon_id = `instr`.id')
            ->where('user_id ='.Yii::$app->user->id)
            ->all();

        
//        $userStatistics = array_replace_recursive($userInstr, $userStatistics);

        return $queryUser;
    }

}