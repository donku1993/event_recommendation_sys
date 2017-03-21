<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    protected static $available_time_array = [
        'prefix' => 'available_time',
        'value' => [
        	0 => '早上 09:00-12:00',
        	1 => '下午 12:00-18:00',
        	2 => '晚上 18:00-22:00',
        ]
    ];

    protected static $location_array = [
        'prefix' => 'location',
        'value' => [
        	0 => '花地瑪堂區',
            1 => '聖安多尼堂區',
            2 => '大堂區',
            3 => '望德堂區',
            4 => '風順堂區',
            5 => '嘉模堂區',
            6 => '聖方濟各堂區',
            7 => '氹仔區',
            8 => '路環區',
        ]
    ];

    protected static $career_array = [
        'prefix' => 'career',
        'value' => [
            0 => '其它',
            1 => '學生',
            2 => '教育業',
            3 => '服務業',
            4 => '衛生保健業',
            5 => '資訊業',
        ]
    ];

    protected static $interest_skills_array = [
        'prefix' => 'interest_skills',
        'value' => [
            0 => '其它',
            1 => '單人運動',
            2 => '團隊運動',
            3 => '旅行',
            4 => '烹飪',
            5 => '下棋',
            6 => '樂器演奏',
            7 => '閱讀',
            8 => '攝影',
            9 => '寫作',
            10 => '平面設計',
            11 => '志願者',
            12 => '擅於表達',
            13 => '擅於領導',
            14 => '擅於社交',
            15 => '編程',
            16 => '急救',
            17 => '財務管理',
            18 => '企劃',
        ]
    ];

    protected static $user_type_array = [
        'prefix' => 'user_type',
        'value' => [
            0 => '系統管理員',
            1 => '普通會員',
            2 => '活躍會員',
            3 => '組職管理員',
        ]
    ];

    protected static $event_type_array = [
        'prefix' => 'event_type',
        'value' => [
            0 => '其他',
            1 => '公益講座',
            2 => '公益義賣',
            3 => '教育助學',
            4 => '關懷訪視',
            5 => '慈善捐贈',
            6 => '志工培訓',
        ]
    ];

    protected static $event_status_array = [
        'prefix' => 'event_status',
        'value' => [
            0 => '接受報名中',
            1 => '報名已截止',
            2 => '活動已完結',
        ]
    ];

    protected static $group_status_array = [
        'prefix' => 'group_status',
        'value' => [
            0 => '新提交',
            1 => '審批中',
            2 => '已批準',
            3 => '已拒絕',
        ]
    ];

    protected static $users_groups_relation_type_array = [
        'prefix' => 'users_groups_relation_type',
        'value' => [
            'manage' => 0,
            'marked' => 1,
        ]
    ];

    protected static $user_gender_array = [
        'prefix' => 'user_gender',
        'value' => [
            'm' => 0,
            'f' => 1
        ]
    ];

    public static function getConstantArray(string $array_name)
    {
        switch ($array_name)
        {
            case 'available_time':
                return self::$available_time_array;
                break;
            case 'location':
                return self::$location_array;
                break;
            case 'career':
                return self::$carrer_array;
                break;
            case 'interest_skills':
                return self::$interest_skills_array;
                break;
            case 'user_type':
                return self::$user_type_array;
                break;
            case 'event_type':
                return self::$event_type_array;
                break;
            case 'event_status':
                return self::$event_status_array;
                break;
            case 'group_status':
                return self::$group_status_array;
                break;
            case 'users_groups_relation_type':
                return self::$users_groups_relation_type_array;
                break;
            case 'user_gender':
                return self::$user_gender_array;
                break;
            default:
                return null;
        }
    }
/*
    public static function UserConstantArray()
    {
        return [ 'user_constant_array' => [
            self::$available_time_array['prefix'] => self::$available_time_array,
            self::$location_array['prefix'] => self::$location_array,
            self::$career_array['prefix'] => self::$career_array,
            self::$interest_skills_array['prefix'] => self::$interest_skills_array,
            self::$user_type_array['prefix'] => self::$user_type_array,
        ]];
    }

    public static function EventConstantArray()
    {
        return [ 'event_constant_array' => [
            self::$location_array['prefix'] => self::$location_array,
            self::$interest_skills_array['prefix'] => self::$interest_skills_array,
            self::$event_type_array['prefix'] => self::$event_type_array,
            self::$event_status_array['prefix'] => self::$event_status_array,
        ]];
    }

    public static function GroupConstantArray()
    {
        return [ 'group_constant_array' => [
            self::$location_array['prefix'] => self::$location_array,
            self::$group_status_array['prefix'] => self::$group_status_array,
        ]];
    }
*/
    public static function AllConstantArray()
    {
        return ['constant_array' => [
            self::$available_time_array['prefix'] => self::$available_time_array,
            self::$location_array['prefix'] => self::$location_array,
            self::$career_array['prefix'] => self::$career_array,
            self::$interest_skills_array['prefix'] => self::$interest_skills_array,
            self::$user_type_array['prefix'] => self::$user_type_array,
            self::$event_type_array['prefix'] => self::$event_type_array,
            self::$event_status_array['prefix'] => self::$event_status_array,
            self::$group_status_array['prefix'] => self::$group_status_array,
        ]];
    }

    public static function JsonDataConverter(array $data, string $column, string $array_name)
    {
        $jsonData = null;
        $array = self::getConstantArray($array_name);

        if ($array)
        {
            foreach ($array['value'] as $key => $value)
            {
                $var_name = $column . '_' . $key;
                $jsonData[$key] = (isset($data[$var_name])) ? true : false;

                if ($jsonData[$key])
                {
                    unset($data[$var_name]);
                }
            }
        }

        $data[$column] = (object) $jsonData;

        return $data;
    }
}
