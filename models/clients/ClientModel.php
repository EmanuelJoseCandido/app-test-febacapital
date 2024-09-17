<?php

namespace app\models\clients;

use yii\db\ActiveRecord;

class ClientModel extends ActiveRecord
{
    /**
     * Define the associated table for this ActiveRecord.
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * Define the relation between Client and Address.
     */
    public function getAddress()
    {
        return $this->hasOne(AddressModel::class, ['client_id' => 'id']);
    }


    /**
     * Validation rules
     */
    public function rules()
    {
        return [
            [['name', 'cpf', 'sex'], 'required'],
            ['name', 'string', 'max' => 255],
            ['cpf', 'match', 'pattern' => '/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', 'message' => 'Invalid CPF format.'],
            ['sex', 'in', 'range' => ['M', 'F'], 'message' => 'Sex must be either M (Male) or F (Female).'],
        ];
    }

    /**
     * Attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'cpf' => 'CPF',
            'sex' => 'Sex',
        ];
    }
}
