<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GlomeTest extends TestCase
{
    use DatabaseTransactions;

    /*public function test_creating_a_new_glome_account()
    {
      
        $response = $this->call('POST', '/api/glome/create');
        dd($response);
    }
    */

    public function test_showing_a_glome_account()
    {
        $response = $this->call('POST', '/api/glome/show/gesbg_17184f_5333c63a88204695cb5169b34b5f26e02015111758098918973150');
        dd($response);
    }

}
