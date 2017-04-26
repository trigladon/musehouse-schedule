<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 22.04.17
 * Time: 18:09
 */

namespace app\modules\teacher\helpers;

ini_set('xdebug.var_display_max_depth', 15);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

use yii\db\Query;
use Yii;
use DateTime;

class Statistics
{
    public static function userStatistics($currentMonth = '', $changes = ''){

        if (!$currentMonth){
            $dateTime = new DateTime("now");
        }else{
            $dateTime = new DateTime($currentMonth);
        }

        $dateTime->modify('first day of this month');
        $dateTime->modify('+2 weeks'.$changes);
        $dateTime->modify('-1 month');

        $startMonth = $dateTime->format('U'); // first month to show of 3, for array arranging

        $dateTime->modify('first day of this month');
        $dateTime->modify('midnight');

        $startDay = $dateTime->format('U')-1; // start searching date in timestamp

        $dateTime->modify('+2 weeks +2 month');
        $dateTime->modify('last day of this month');
        $dateTime->modify('+1 day');

        $endDay = $dateTime->format('U')-1; // end searching date in timestamp

        // START receiving all statuses
        $queryStatuses = (new Query())
            ->select(['id', 'color'])
            ->from('statusschedule')
            ->all();

        $statuses = [];
        foreach ($queryStatuses as $value){
            $curentStatus = [
                $value['id'] => [
                    'id' => $value['id'],
                    'color' => $value['color'],
                    'qnt_lessons' => 0,
                ]
            ];

            $statuses = array_replace_recursive($statuses, $curentStatus);
        }
        // END receiving all statuses


        for ($m = 1; $m<= 3; $m++){
            $monthArray[date('Y', $startMonth)][date('F', $startMonth)]['results'] = $statuses;
            $startMonth += 60*60*24*30;
        }

        // START receiving all user instruments
        $queryUserInstr = (new Query())
            ->select(['u_instr.user_id', 'u_instr.instricon_id', '`instr`.instr_name', '`instr`.icon', 'u.first_name', 'u.last_name'])
            ->from('userinstr u_instr')
            ->leftJoin('instricon `instr`', 'u_instr.instricon_id = `instr`.id')
            ->leftJoin('user u', 'u_instr.user_id = u.id')
//            ->where(['u_instr.user_id' => 76])
            ->all();//todo add where with user if master or not

        $userInstr = [];
        foreach ($queryUserInstr as $value){
            $curentUserInstr = [
                $value['first_name'].' '.$value['last_name'] => [
                    $value['instr_name'] => [
                        'id' => $value['instricon_id'],
                        'icon' => $value['icon'],
                        'name' => $value['instr_name'],
                        'data' => [],
                    ]
                ]
            ];

            $userInstr = array_replace_recursive($userInstr, $curentUserInstr);
            $userInstr[$value['first_name'].' '.$value['last_name']]['']['data'] = $monthArray;
            $userInstr[$value['first_name'].' '.$value['last_name']][$value['instr_name']]['data'] = $monthArray;
            krsort($userInstr[$value['first_name'].' '.$value['last_name']]);
        }
        // END receiving all user instruments



        $queryData = Yii::$app->db->createCommand('
            SELECT ussch.user_id, u.first_name, u.last_name, 
            FROM_UNIXTIME(ussch.lesson_start, \'%Y\') as `year`, FROM_UNIXTIME(ussch.lesson_start, \'%c\') as month_number, FROM_UNIXTIME(ussch.lesson_start, \'%M\') as `month`, 
            ussch.instricon_id, `instr`.instr_name, `instr`.icon as instr_icon,
            ussch.statusschedule_id, st.color as status_color,
            COUNT(ussch.lesson_start) as quant
            FROM userschedule ussch
            LEFT JOIN user u
            ON ussch.user_id = u.id
            LEFT JOIN instricon `instr`
            ON ussch.instricon_id = `instr`.id
            LEFT JOIN statusschedule st
            ON ussch.statusschedule_id = st.id
            WHERE ussch.lesson_start BETWEEN '.$startDay.' AND '.$endDay.'
            GROUP BY ussch.user_id, FROM_UNIXTIME(ussch.lesson_start, \'%Y\'), FROM_UNIXTIME(ussch.lesson_start, \'%c\'), FROM_UNIXTIME(ussch.lesson_start, \'%M\'), ussch.instricon_id, ussch.statusschedule_id  
        ')->queryAll(); //todo add where with user if master or not

//        WHERE ussch.lesson_start BETWEEN '.$startDay.' AND '.$endDay.' AND ussch.user_id REGEXP "76"
//        WHERE ussch.lesson_start BETWEEN '.$startDay.' AND '.$endDay.' AND ussch.user_id = 76
        $userStatistics = [];

        foreach ($queryData as $value){
            $curentData = [
                $value['first_name'].' '.$value['last_name'] => [
                    $value['instr_name'] => [
                        'id' => $value['instricon_id'],
                        'icon' => $value['instr_icon'],
                        'name' => $value['instr_name'],
                        'data' => [
                            $value['year'] => [
                                $value['month'] => [
                                    'results' => [
                                        $value['statusschedule_id'] => [
                                            'id' => $value['statusschedule_id'],
                                            'color' => $value['status_color'],
                                            'qnt_lessons' => $value['quant'],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $userStatistics = array_replace_recursive($userStatistics, $curentData);
        }

        // START template preparing
        $queryUser = (new Query())->select(['first_name', 'last_name'])
            ->from('user')
            ->all(); //todo add where whith user if master or not

        $template = [];
        foreach ($queryUser as $value) {
            $curentUserTemplate = [
                $value['first_name'] . ' ' . $value['last_name'] => []
            ];
            $curentUserTemplate[$value['first_name'].' '.$value['last_name']] = $userInstr[$value['first_name'].' '.$value['last_name']];
            $template = array_replace_recursive($template, $curentUserTemplate);
        }
        // END template preparing
//
        $userStatistics = array_replace_recursive($userInstr, $userStatistics);




//        return $monthArray;
//        return $userInstr;
//        return $curentUserInstr;
//        return $template;
        return $userStatistics;
    }

    public static function monthsToShow($currentMonth = '', $changes = ''){

        if (!$currentMonth){
            $dateTime = new DateTime("now");
        }else{
            $dateTime = new DateTime($currentMonth);
        }

        $dateTime->modify('first day of this month');
        $dateTime->modify('+2 weeks'.$changes);
        $info = $dateTime->format('F Y');
        $currentMonth = $dateTime->format('Y-m-d');
        $dateTime->modify('-1 month');

        $startMonth = $dateTime->format('U'); // first month to show of 3, for array arranging

        for ($m = 1; $m<= 3; $m++){
            $monthsArray['months'][date('F', $startMonth)] = date('Y', $startMonth);
            $startMonth += 60*60*24*30;
        }

        $monthsArray['currentMonth'] = $currentMonth;
        $monthsArray['info'] = $info;


        return $monthsArray;
    }

}