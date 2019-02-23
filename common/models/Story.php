<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "story".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $entity
 * @property integer $during
 * @property integer $type
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class Story extends \yii\db\ActiveRecord
{

    /**
     * 故事状态
     */

    public static $cateStrArray = [ 1=>'一般',
        2=>'推荐',
        3=>'封面'];
    public static function allCategory()
    {
        return self::$cateStrArray;
    }

    public function getCateStr()
    {
        return self::$cateStrArray[$this->type];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'entity', 'during', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'during', 'type', 'status'], 'integer'],
            [['entity'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 13],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'user_id' => '用户ID',
            'entity' => '实体存储地址',
            'during' => '语音时间/ms',
            'type' => '类型',
            'status' => '1-正常 2-关闭 3-异常 4-删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getSecToTime()
    {
        $result = '0';
        if ($this->during>0) {
            $hour = floor($this->during/3600/1000);
            $minute = floor(($this->during/1000-3600 * $hour)/60);
            $second = floor((($this->during/1000-3600 * $hour) - 60 * $minute) % 60);
            $micSec = $this->during - ($second  + $minute * 60 + $hour * 3600) * 1000;
            $result = $micSec.'\'\'';
            $result = $second ? $second.'\''.$result : $result;
            $result = $minute ? $minute.'m'.$result : $result;
            $result = $hour ? $hour.'h'.$result : $result;
        }
        return $result;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPlay($entity)
    {
        return $entity;
    }
}
