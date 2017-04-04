<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 29.03.17
 * Time: 14:19
 */

namespace app\modules\teacher\models;


use yii\base\Model;
use DateTime;

class Calendar extends Model
{

    public static function calendarArray($date = "now"){

        $calendarArray = [];

        $now = new DateTime($date);
        $now->modify('first day of this month');
        $now->modify('last Monday -1 day');
        for ($i=1; $i<=42; $i++){
            $now->modify('+1 day');
            $curent = [
                'year_'.$now->format('Y') => $now->format('Y'),
                $now->format('Y') => [
                    'week' => [
                        'week_'.$now->format('W') => $now->format('W'),
                        $now->format('W') => [
                            'month' => [
                                'month_'.$now->format('m') => $now->format('m'),
                                $now->format('m') => [
                                    'day' => [
                                        'day_'.$now->format('d') => $now->format('d'),
                                        $now->format('d') => [
                                            'day_of_the_week' => $now->format('N')
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            $calendarArray = array_replace_recursive($calendarArray, $curent);
        }

        return $calendarArray;
    }

    public static function monthToShow($currentDate = '', $changes = ''){

        if (!$currentDate){
            $currentDate = date('Y-m-d');
            $month = new DateTime($currentDate);
        }else{
            $month = new DateTime($currentDate);
        }

        $month->modify('first day of this month');
        $month->modify('+2 weeks'.$changes);
        $monthToShow = [
            'toShow' => $month->format('Y-m-d'),
            'month' => $month->format('F'),
            'year' => $month->format('Y'),
            ];

        return $monthToShow;
    }
}