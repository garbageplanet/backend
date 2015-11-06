<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrashTest extends TestCase
{
    use DatabaseTransactions;

    public function test_making_a_point_of_lat_long()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
            'cleaned_at' => '2015-11-06',

        ]);
        $trashPoint = $trash->makePoint();
        $this->assertEquals(1, $trashPoint);

    }
}
