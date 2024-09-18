<?php

use yii\db\Migration;

/**
 * Class m240918_004356_seed_clients_and_addresses_tables
 */
class m240918_004356_seed_clients_and_addresses_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertFakeClientsAndAddresses();
    }

    private function insertFakeClientsAndAddresses()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->insert('clients', [
                'name' => $faker->name,
                'cpf' => $faker->unique()->numerify('###.###.###-##'),
                'sex' => $faker->randomElement(['M', 'F']),
                'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'updated_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            ]);

            $clientId = Yii::$app->db->lastInsertId;

            $this->insert('addresses', [
                'client_id' => $clientId,
                'zip' => $faker->postcode,
                'street' => $faker->streetName,
                'number' => $faker->buildingNumber,
                'city' => $faker->city,
                'state' => $faker->stateAbbr,
                'complement' => $faker->optional()->streetAddress,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Para remover os dados inseridos, você pode optar por deletar os dados da tabela
        $this->delete('addresses');
        $this->delete('clients');
    }
}
