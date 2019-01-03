<?php
/**
 * Created by PhpStorm.
 * User: bdionis
 * Date: 29.03.17
 * Time: 14:19
 */

namespace app\modules\teacher\helpers;

use DateTime;

class Calendar
{

    public static function calendarArray($date = "now", $daysToShow = 42, $whtsh = 'month'){

        $calendarArray = [];

        $now = new DateTime($date);

        if($whtsh == 'month'){
            $now->modify('first day of this month');
            $now->modify('last Monday -1 day');
        }else{
            $now->modify('-1 day');
        }

        for ($i=1; $i<=$daysToShow; $i++){
            $now->modify('+1 day');
            $curent = [
                'year_'.$now->format('Y') => $now->format('Y'),
                $now->format('Y') => [
                    'week' => [
                        'week_'.$now->format('W') => $now->format('U'),
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

    public static function monthToShow($currentDate = '', $changes = '', $whtsh = 'month'){

        switch ($whtsh) {
            case "month":
                $month = new DateTime($currentDate);
                $daysToShow = 42;
                $month->modify('first day of this month');
                $month->modify('+2 weeks'.$changes);
                $currentDate = $month->format('Y-m-d');
                break;
            case 'week':
                $month = new DateTime();
                $daysToShow = 7;
                $cur = $month->format('U');
                $ch = $currentDate - $cur;
                $month->modify('+'.$ch.'sec');
                if ($month->format('N') != 1){
                    $month->modify('last Monday');
                }
                if ($changes){
                    $month->modify($changes);
                }
                $currentDate = $month->format('U');
                $year = $month->format('W:Y');
                $monthCheck = $month->format('W:F');
                break;
            case 'day':
                $month = new DateTime($currentDate);
                if ($changes){
                    $month->modify($changes);
                }
                $currentDate = $month->format('Y-m-d');
                $daysToShow = 1; break;
            default:
                $daysToShow = 42;
                $month = new DateTime();
                break;
        }

            $monthToShow = [
                'toShow' => $month->format('Y-m-d'),
                'month' => $month->format('F'),
                'year' => $month->format('Y'),
                'daysToShow' => $daysToShow,
                'currentDate' => $currentDate,
            ];

            return $monthToShow;
    }

    public static function weekDaysToShow($toShow, $whtsh){

        $weekDays = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];

        if($whtsh == 'day'){
            $toShow = explode('-', $toShow);
            $weekDaysToShow[] = $weekDays[date('N', mktime(0, 0,0 , $toShow[1], $toShow[2], $toShow[0]))];
        }else{
            $weekDaysToShow = $weekDays;
        }

        return $weekDaysToShow;
    }
}