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
        ]);
        $trashPoint = $trash->makePoint();
        $this->assertEquals(1, $trashPoint);

    }

    public function test_create_trash_and_add_types()
    {
        $user = factory(App\User::class)->create();
        $trash = factory(App\Trash::class)->create([
            'marked_by' => $user->id,
        ]);
        $trash->addTypes('type1,type2');
        //assertions?
    }
}
