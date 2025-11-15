<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_prices_unit".
 *
 * @property int $id
 * @property int $product_id
 * @property int $unit_id
 * @property float $price
 * @property string $datetime
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Products $product
 * @property Units $unit
 */
class ProductPricesUnit extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_prices_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'unit_id', 'price', 'datetime'], 'required'],
            [['product_id', 'unit_id'], 'integer'],
            [['price'], 'number'],
            [['datetime', 'created_at', 'updated_at'], 'safe'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Units::class, 'targetAttribute' => ['unit_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'unit_id' => 'Unit ID',
            'price' => 'Price',
            'datetime' => 'Datetime',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Units::class, ['id' => 'unit_id']);
    }

}
