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
     * Deferred task ID to be used across tests.
     *
     * @var string
     */
    private $deferredTaskId;

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
            'email' => fake()->userName().'@ramp.com';,
            'first_name' => 'Test',
            'last_name' => 'User',
            'role' => 'GUEST_USER',
        ]);

        // Check if the response is an array
        $this->assertIsArray($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertIsString($response['id']);

        // Store the deferred task ID for later use
        $this->deferredTaskId = $response['id'];
    }

    public function test_users_fetch_deferred_task_status()
    {
        $response = $this->rampUsers->fetchDeferredTaskStatus([
            'task_id' => $this->deferredTaskId
        ]);

        // Expected structure
        $expectedStructure = [
            'context' => [
                'acting_user_id' => 'string',
            ],
            'data' => [
                'user_id' => 'string',
            ],
            'id' => 'string',
            'status' => 'string',
        ];
    
        // $this->assertArrayStructure($expectedStructure, $response);

        // // Check if the response is an array
        // $this->assertIsArray($response);

        // $this->assertArrayHasKey('context', $response);
        // $this->assertIsArray($response['context']);
        // $this->assertIsString($response['context']['acting_user_id']);

        // $this->assertArrayHasKey('data', $response);
        // $this->assertIsArray($response['data']);
        // $this->assertIsString($response['data']['user_id']);

        // $this->assertIsString($response['id']);
        // $this->assertIsString($response['status']);
    }

    /**
     * Assert that an array matches the given structure.
     *
     * @param array $expectedStructure
     * @param array $array
     * @return void
     */
    private function assertArrayStructure(array $expectedStructure, array $array)
    {
        foreach ($expectedStructure as $key => $type) {
            $this->assertArrayHasKey($key, $array);
    
            if (is_array($type)) {
                $this->assertIsArray($array[$key]);
                $this->assertArrayStructure($type, $array[$key]);
            } else {
                switch ($type) {
                    case 'string':
                        $this->assertIsString($array[$key]);
                        break;
                    case 'array':
                        $this->assertIsArray($array[$key]);
                        break;
                    // Add more types as needed
                }
            }
        }
    }
}
