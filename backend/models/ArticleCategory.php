<?php

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $is_deleted
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sort'], 'required'],
            [['intro'], 'string'],
            [['sort', 'is_deleted'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
            'is_deleted' => '简介',
        ];
    }


    public static function allArticleCategory(){

        $article=self::find()->all();

        $arr=[];
        foreach ($article as $v ){

            $arr[$v->id]=$v->name;

        }

        return $arr;
    }


}
