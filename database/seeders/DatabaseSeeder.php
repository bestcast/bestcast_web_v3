<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\ConnectRelationshipsSeeder;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\LanguagesTableSeeder;
use Database\Seeders\MoviesTableSeeder;
use Database\Seeders\ShowsTableSeeder;
use Database\Seeders\SubscriptionTableSeeder;
use Database\Seeders\MenuTableSeeder;
use Database\Seeders\NotificationTableSeeder;
use Database\Seeders\ProfileiconTableSeeder;
use Database\Seeders\UsersProfileTableSeeder;
use Database\Seeders\BannerTableSeeder;
use Database\Seeders\BlocksTableSeeder;
use Database\Seeders\AppnotifyTableSeeder;
use Database\Seeders\MobileappTableSeeder;
use Database\Seeders\PaymentgatewayTableSeeder;
use Database\Seeders\ReferTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

            $this->call(PermissionsTableSeeder::class);
            $this->call(RolesTableSeeder::class);
            $this->call(ConnectRelationshipsSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(CoreConfigTableSeeder::class);
            $this->call(PostTableSeeder::class);
            $this->call(GenresTableSeeder::class);
            $this->call(LanguagesTableSeeder::class);
            $this->call(MoviesTableSeeder::class);
            $this->call(ShowsTableSeeder::class);
            $this->call(BannerTableSeeder::class);
            $this->call(BlocksTableSeeder::class);
            $this->call(SubscriptionTableSeeder::class);
            $this->call(MenuTableSeeder::class);
            $this->call(NotificationTableSeeder::class);
            $this->call(ProfileiconTableSeeder::class);
            $this->call(UsersProfileTableSeeder::class);
            $this->call(AppnotifyTableSeeder::class);
            $this->call(MobileappTableSeeder::class);
            $this->call(PaymentgatewayTableSeeder::class);
            $this->call(ReferTableSeeder::class);

        Model::reguard();
        // \App\User::factory(10)->create();
    }
}
