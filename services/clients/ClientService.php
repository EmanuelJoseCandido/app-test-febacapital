<?php

namespace app\services\clients;

use app\models\clients\ClientModel;
use Yii;
use yii\web\BadRequestHttpException;
use yii\data\ActiveDataProvider;
use app\services\clients\AddressService;

/**
 * ClientService handles the business logic for client operations.
 */
class ClientService
{
    private AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Creates a new client record along with an address.
     *
     * @param array $data
     * @return ClientModel
     * @throws BadRequestHttpException if validation fails
     */
    public function createClient(array $data): ClientModel
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $client = new ClientModel();
            $addressData = $data['address'] ?? null;

            unset($data['address']);

            if ($client->load($data, '') && $client->validate()) {
                if ($client->save()) {
                    if ($addressData) {
                        $addressData['client_id'] = $client->id;
                        $this->addressService->createAddress($addressData);
                    }
                    $transaction->commit();

                    // Load address data
                    $client->populateRelation('address', $this->addressService->getAddressByClientId($client->id));

                    return $client;
                }
            }

            throw new BadRequestHttpException(json_encode($client->errors));
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Lists clients with pagination, filters, and sorting.
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function getClientsList(array $params): ActiveDataProvider
    {
        $query = ClientModel::find()->with('address'); // Eager loading address

        if (!empty($params['name'])) {
            $query->andFilterWhere(['like', 'name', $params['name']]);
        }

        if (!empty($params['cpf'])) {
            $query->andFilterWhere(['cpf' => $params['cpf']]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['limit'] ?? 10,
                'page' => ($params['offset'] ?? 0) / ($params['limit'] ?? 10),
            ],
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
                'attributes' => [
                    'name',
                    'cpf',
                ],
            ],
        ]);
    }

    /**
     * Retrieves a client by ID with associated address.
     *
     * @param int $id
     * @return ClientModel
     * @throws BadRequestHttpException if client not found
     */
    public function getClientById(int $id): ClientModel
    {
        $client = ClientModel::find()->with('address')->where(['id' => $id])->one();

        if ($client === null) {
            throw new BadRequestHttpException('Client not found');
        }

        return $client;
    }

    /**
     * Updates a client by ID.
     *
     * @param int $id
     * @param array $data
     * @return ClientModel
     * @throws BadRequestHttpException if validation fails
     */
    public function updateClient(int $id, array $data): ClientModel
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $client = $this->getClientById($id);

            if ($client->load($data, '') && $client->validate()) {
                if ($client->save()) {
                    // Optionally update address if provided
                    if (isset($data['address'])) {
                        $addressData = $data['address'];
                        $addressData['client_id'] = $client->id;
                        $this->addressService->updateAddress($client->id, $addressData);
                    }
                    $transaction->commit();

                    // Load address data
                    $client->populateRelation('address', $this->addressService->getAddressByClientId($client->id));

                    return $client;
                }
            }

            throw new BadRequestHttpException(json_encode($client->errors));
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Deletes a client by ID.
     *
     * @param int $id
     * @throws BadRequestHttpException if client not found
     */
    public function deleteClient(int $id): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $client = $this->getClientById($id);

            if (!$client->delete()) {
                throw new BadRequestHttpException('Failed to delete client');
            }

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
