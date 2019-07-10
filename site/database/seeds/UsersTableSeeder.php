<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Default user email
     *
     * @var string
     */
    const DEFAULT_USER_EMAIL = 'suporte@dindigital.com';

    /**
     * Default user password
     *
     * @var string
     */
    const DEFAULT_USER_PASSWORD = 'secret';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 1)->create();

        $this->createDefaultUser();
    }

    /**
     * Create default user if it doesn't exists yet
     */
    private function createDefaultUser()
    {
        $user = new User();

        $defaultUserExists = User::query()
            ->where('email', '=', self::DEFAULT_USER_EMAIL)
            ->exists();

        if (! $defaultUserExists) {
            $user->name = 'Din Digital';
            $user->email = self::DEFAULT_USER_EMAIL;
            $user->email_verified_at = now();
            $user->password = password_hash(self::DEFAULT_USER_PASSWORD, \PASSWORD_DEFAULT);
            $user->remember_token = Str::random(10);
            $user->save();
        }
    }
}
