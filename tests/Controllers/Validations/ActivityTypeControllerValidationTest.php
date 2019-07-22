<?php

namespace Tests\Controllers\Validations;

use App\Models\ActivityType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ActivityTypeControllerValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->signInWithDefaultAdminUser();
    }

    /** @test */
    public function create_activity_type_fails_when_name_is_not_passed()
    {
        $this->post('activity-types', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function update_activity_type_fails_when_name_is_not_passed()
    {
        $activityType = factory(ActivityType::class)->create();

        $this->put('activity-types/'.$activityType->id, ['name' => ''])
            ->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    /** @test */
    public function update_activity_type_fails_when_name_is_duplicate()
    {
        $activityType = factory(ActivityType::class)->create();

        $this->put('activity-types/'.$activityType->id, ['name' => 'Development'])
            ->assertSessionHasErrors(['name' => 'Activity type with same name already exist']);
    }

    /** @test */
    public function allow_update_activity_type_with_valid_name()
    {
        /** @var ActivityType $activityType */
        $activityType = factory(ActivityType::class)->create();

        $this->put('activity-types/'.$activityType->id, ['name' => 'Any Dummy Name'])
            ->assertSessionHasNoErrors();

        $this->assertEquals('Any Dummy Name', $activityType->fresh()->name);
    }
}