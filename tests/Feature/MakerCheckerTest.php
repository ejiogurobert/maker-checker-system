<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AdminRequest;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Jobs\MakerCheckerJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Database\Factories\RequestFactory;
use Database\Factories\UserFactory;
use Database\Factories\AdminRequestFactory;
use Illuminate\Support\Facades\Queue;



class MakerCheckerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function actingAs($registerUser,  $driver = null)
    {

        $token = JWTAuth::fromUser($registerUser);

        $this->withHeader('Authorization', "Bearer{$token}");

        parent::actingAs($registerUser);
        // dd($registerUser);
        return $this;
    }
    public function check_status_and_endorse($request_status = null)

    {
        $user = User::factory()->create();

        // dd($this->actingAs($user));

        $this->actingAs($user);

        $send_request = AdminRequest::factory()->create();
        // dd($send_request);

        $getRequest = $this->call('POST', route('approve', $send_request->id), [

            // '_token' => csrf_token(),

            // 'endorse_type' => $request_status,

            // 'id' => $send_request->id,

            // 'email' => auth()->user()->email,

        ]);

        return $getRequest;
    }
    /** @test */
    public function DeclineRequest()
    {

        $decline_request = $this->check_status_and_endorse('Declined');

        $this->assertEquals(200, $decline_request->getStatusCode());
    }

    /** @test */

    public function ApproveRequest()
    {

        $decline_request = $this->check_status_and_endorse('Approved');

        $this->assertEquals(200, $decline_request->getStatusCode());
    }

    /** @test */



    public function DispatchJob()
    {

        $user = User::factory()->create();

        $this->actingAs($user);

        $send_request = AdminRequest::factory()->create();

        Queue::fake();

        $endorsee_ids = [1,2,3];

        // $endorsee_ids = $endorsee_ids = explode(',', $endorsee);

        $getemails = User::whereIn('id', $endorsee_ids)->select('email')->get()->toArray();

        foreach ($getemails as $mail) {

            dispatch(new MakerCheckerJob($mail, $user->id, $send_request->id));
        }

        Queue::assertPushed(MakerCheckerJob::class);
    }
}
