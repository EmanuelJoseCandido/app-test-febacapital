<?php

namespace app\services\clients;

use app\models\clients\AddressModel;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * AddressService handles the business logic for address operations.
 */
class AddressService
{
    /**
     * Creates a new address record.
     *
     * @param array $data
     * @return AddressModel
     * @throws BadRequestHttpException if validation fails
     */
    public function createAddress(array $data): AddressModel
    {
        $address = new AddressModel();

        if ($address->load($data, '') && $address->validate()) {
            if ($address->save()) {
                return $address;
            }
        }

        throw new BadRequestHttpException(json_encode($address->errors));
    }

    /**
     * Updates an address by client ID.
     *
     * @param int $clientId
     * @param array $data
     * @return AddressModel
     * @throws BadRequestHttpException if validation fails
     */
    public function updateAddress(int $clientId, array $data): AddressModel
    {
        $address = AddressModel::findOne(['client_id' => $clientId]);

        if ($address === null) {
            throw new BadRequestHttpException('Address not found');
        }

        if ($address->load($data, '') && $address->validate()) {
            if ($address->save()) {
                return $address;
            }
        }

        throw new BadRequestHttpException(json_encode($address->errors));
    }

    /**
     * Retrieves an address by client ID.
     *
     * @param int $clientId
     * @return AddressModel
     * @throws BadRequestHttpException if address not found
     */
    public function getAddressByClientId(int $clientId): AddressModel
    {
        $address = AddressModel::findOne(['client_id' => $clientId]);

        if ($address === null) {
            throw new BadRequestHttpException('Address not found');
        }

        return $address;
    }
}
