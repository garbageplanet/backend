<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrashesControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    public function test_index_returning_all_trashes_as_json()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',

        ]);
        $response = $this->call('GET', '/api/trashes');
        $this->assertResponseOk();
        $this->seeJson();
    }

    public function test_withinbounds_is_returning_trashes_as_json_withinbounds()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',
            'lat' => '60.123',
            'lng' => '23',
        ]);
        $trash->makePoint();
        $response = $this->call('GET', '/api/trashes/withinbounds', ['bounds' => '60.5, 25, 60, 20']);
        $this->assertEquals(200, $response->status());
        $this->assertContains('"id":'. $trash->id. ',', $response->content());
    }

    public function test_store_new_trash()
    {
        $user = factory(App\User::class)->create();
        $this->actingAs($user);
        $response = $this->call('POST', '/api/trashes', [
                'marked_by' => $user->id,
                'marked_at' => '2015-11-06',
                'lat' => '60.1521',
                'lng' => '24.124',
                'amount' => 4,
                'status' => 'lirum',
                'cleaned_at' => '2015-11-06',
        ]);
        $this->assertContains('"lat":"60.1521', $response->content());
        $this->assertEquals(true, $response->getOriginalContent()->wasRecentlyCreated);
    }

    public function test_show_trash_by_id()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',
            'lat' => '60.123',
            'lng' => '23',
        ]);
        $response = $this->call('GET', '/api/trashes/'. $trash->id);
        $this->assertEquals(200, $response->status());
        $this->assertContains('"id":'. $trash->id. ',', $response->content());
    }

    public function test_update_trash_by_id()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',
            'lat' => '60.123',
            'lng' => '23',
            'amount' => 1,
        ]);
        $response = $this->call('PUT', '/api/trashes/'. $trash->id, [
            'amount' => 2,
        ]);
        $this->assertEquals(200, $response->status());
        $this->assertContains('"amount":2,', $response->content());
    }

    public function test_delete_trash_by_id()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',
            'lat' => '60.123',
            'lng' => '23',
            'amount' => 1,
        ]);
        $response = $this->call('DELETE', '/api/trashes/'. $trash->id);
        $this->assertResponseOk();
    }
}
