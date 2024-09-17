<?php

namespace app\models\clients;

use yii\db\ActiveRecord;

class AddressModel extends ActiveRecord
{
    /**
     * Define a tabela associada a este ActiveRecord.
     */
    public static function tableName()
    {
        return 'addresses'; 
    }

    /**
     * Define a relação entre Address e Client
     */
    public function getClient()
    {
        return $this->hasOne(ClientModel::class, ['id' => 'client_id']);
    }

    /**
     * Regras de validação
     */
    public function rules()
    {
        return [
            [['client_id', 'zip', 'street', 'number', 'city', 'state'], 'required'],
            ['zip', 'match', 'pattern' => '/^\d{5}-\d{3}$/', 'message' => 'Invalid ZIP code format.'],
            ['state', 'string', 'max' => 2, 'message' => 'State should be a 2-letter code (e.g., SP, RJ).'],
            ['complement', 'string', 'max' => 255], // Optional field
        ];
    }

    /**
     * Rótulos amigáveis para os atributos
     */
    public function attributeLabels()
    {
        return [
            'zip' => 'ZIP Code',
            'street' => 'Street',
            'number' => 'Number',
            'city' => 'City',
            'state' => 'State',
            'complement' => 'Complement',
        ];
    }
}
