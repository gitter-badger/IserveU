<?php


class TestCase extends Illuminate\Foundation\Testing\TestCase

{
    
    protected $user;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function signIn($user = null)
    {
        if(!$user){
            $user = factory(App\User::class)->create();
        }
        return $this->authenticate($user);
    }

    public function authenticate($user)
    {
        if (Auth::user()){
            Auth::logout();
        }

        Auth::loginUsingId($user->id);

        $this->post( '/authenticate', ['email' => $user->email, 'password' => 'abcd1234'] );
        $content = json_decode($this->response->getContent());
        $this->assertObjectHasAttribute('token', $content, 'Token does not exists');


        $this->user = $user;
        $this->token = $content->token;
        $this->actingAs($user);

        return $this;
    }
}
