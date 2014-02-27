<?php  namespace Palmabit\Authentication\Tests;
use Palmabit\Authentication\Repository\EloquentUserProfileRepository;

/**
 * Test EloquentUserProfileRepositoryTest
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
class EloquentUserProfileRepositoryTest extends DbTestCase {

    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @test
     **/
    public function it_create_a_new_profile()
    {
        $repo = new EloquentUserProfileRepository();

        $data = $this->prepareFakeData();
        $profile = $repo->create($data);

        $this->assertInstanceOf('\Palmabit\Authentication\Models\UserProfile', $profile);
        $this->assertEquals($data['user_id'], $profile->user_id);
    }

    protected function prepareFakeData()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 100),
            'code' => $this->faker->text(20),
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'phone' => $this->faker->phoneNumber(),
            'vat' => $this->faker->randomNumber(12),
            'cf' => $this->faker->text(12),
            'billing_address' => $this->faker->address(),
            'billing_address_zip' => $this->faker->postcode(),
            'shipping_address' => $this->faker->address(),
            'shipping_address_zip' => $this->faker->postcode(),
            'billing_state' => $this->faker->country(),
            'billing_city' => $this->faker->country(),
            'billing_country' => $this->faker->country(),
            'shipping_state' => $this->faker->country(),
            'shipping_city' => $this->faker->country(),
            'shipping_country' => $this->faker->country()
            ];
    }

}
 