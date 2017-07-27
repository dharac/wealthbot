 <?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $status = 'active';
        $pswd = 'wm@123';

        $user = User::create(
                array(
                'username'      => 'ashish',
                'email'         => 'ashish@webmechanic.in',
                'first_name'    => 'Developer',
                'last_name'     => 'Webmechanic',
                'coucod'        => 1,
                'gender'        => 'male',
                'sec_question'  => 1,
                'sec_answer'    => 'test',
                'dob'           => $now,
                'created_by'    => 1,
                'modified_by'   => 1,
                'status'        => $status,
                'confirmed'     => 1,
                'terms'         => 1,
                'updated_at'    => $now,
                'created_at'    => $now,
                'password'      => bcrypt($pswd),
                )
        );

        $user->roles()->attach(1);

        $user1 = User::create(
            array(
                'first_name'    => 'Chetan',
                'last_name'     => 'Chitte',
                'email'         => 'chetan@webmechanic.in',
                'username'      => 'chetan',
                'updated_at'    => $now,
                'created_at'    => $now,
                'coucod'        => 1,
                'gender'        => 'male',
                'sec_question'  => 1,
                'sec_answer'    => 'test',
                'dob'           => $now,
                'terms'         => 1,
                'created_by'    => 1,
                'modified_by'   => 1,
                'status'        => $status,
                'confirmed'     => 1,
                'password'      => bcrypt($pswd),
            )
        );

        $user1->roles()->attach(2);


        $user2  = User::create(
            array(
                'first_name'    => 'Harjot',
                'last_name'     => 'Saini',
                'email'         => 'harjot@webmechanic.in',
                'username'      => 'harjot',
                'updated_at'    => $now,
                'created_at'    => $now,
                'coucod'        => 1,
                'gender'        => 'male',
                'sec_question'  => 1,
                'sec_answer'    => 'test',
                'dob'           => $now,
                'terms'         => 1,
                'created_by'    => 1,
                'modified_by'   => 1,
                'status'        => $status,
                'confirmed'     => 1,
                'password'      => bcrypt($pswd),
            )
        );

        $user2->roles()->attach(3);
    }
}   