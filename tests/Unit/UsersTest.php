<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\TestCase;
use R0aringthunder\RampApi\Ramp;

/**
 * Unit test for the Users API of the Ramp package.
 *
 * This test verifies that the `list` method of the `Users` API returns the expected structure
 * and contents for a list of users.
 *
 * @package Tests\Unit
 */
class UsersTest extends TestCase
{
    /**
     * Instance of the Users API from the Ramp package.
     *
     * @var \R0aringthunder\RampApi\Users
     */
    protected $rampUsers;

    /**
     * Set up the test environment.
     *
     * This method initializes the Ramp API Users instance before each test is run.
     * It also calls the parent `setUp` method to ensure the Laravel environment is prepared.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->rampUsers = (new Ramp())->users;
    }

    /**
     * Test the `list` method of the Users API.
     *
     * This test verifies that the `list` method returns an array and that the structure of
     * the data is as expected. It checks that each user item contains the required keys and
     * validates their types.
     *
     * @return void
     */
    public function test_users_list()
    {
        $response = $this->rampUsers->list();

        // Check if the response is an array
        $this->assertIsArray($response);

        // Check if 'page' key exists and has a 'next' key with value NULL
        $this->assertArrayHasKey('page', $response);
        $this->assertIsArray($response['page']);
        $this->assertArrayHasKey('next', $response['page']);
        $this->assertNull($response['page']['next']);

        // Check if 'data' key exists and is an array
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
        $this->assertNotEmpty($response['data']);

        // Check that every item in the 'data' array has the required keys
        foreach ($response['data'] as $item) {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('employee_id', $item);
            $this->assertArrayHasKey('role', $item);
            $this->assertArrayHasKey('first_name', $item);
            $this->assertArrayHasKey('business_id', $item);
            $this->assertArrayHasKey('is_manager', $item);
            $this->assertArrayHasKey('status', $item);
            $this->assertArrayHasKey('custom_fields', $item);
            $this->assertArrayHasKey('department_id', $item);
            $this->assertArrayHasKey('phone', $item);
            $this->assertArrayHasKey('location_id', $item);
            $this->assertArrayHasKey('manager_id', $item);
            $this->assertArrayHasKey('entity_id', $item);
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('email', $item);
            $this->assertArrayHasKey('last_name', $item);

            // Check the types of the values
            $this->assertIsString($item['role']);
            $this->assertIsString($item['first_name']);
            $this->assertIsString($item['business_id']);
            $this->assertIsBool($item['is_manager']);
            $this->assertIsString($item['status']);
            $this->assertIsArray($item['custom_fields']);
            $this->assertIsString($item['department_id']);
            $this->assertIsString($item['location_id']);
            $this->assertIsString($item['entity_id']);
            $this->assertIsString($item['id']);
            $this->assertIsString($item['email']);
            $this->assertIsString($item['last_name']);
        }
    }

    public function test_users_create_invite()
    {
        $response = $this->rampUsers->createInvite([
            'idempotency_key' => uniqid(),
            'email' => 'test@test.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'role' => 'IT_ADMIN',
        ]);

        $this->assertArrayHasKey('id', $response);
        $this->assertIsString($response['id']);
    }
}
