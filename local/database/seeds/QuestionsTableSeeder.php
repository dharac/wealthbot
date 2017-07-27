<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\SecurityQuestion;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$now = Carbon::now();
        //Security Questions
        SecurityQuestion::create(
            array(
                'question' => 'What is the first name of your favorite uncle?',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        SecurityQuestion::create(
            array(
                'question' => 'Where did you meet your spouse?',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );


        SecurityQuestion::create(
            array(
                'question' => "What is your oldest cousins name?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        SecurityQuestion::create(
            array(
                'question' => "What is your youngest childs nickname?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );


        SecurityQuestion::create(
            array(
                'question' => "What is your oldest childs nickname?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );



        SecurityQuestion::create(
            array(
                'question' => "What is the first name of your oldest niece?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );


        SecurityQuestion::create(
            array(
                'question' => "What is the first name of your oldest nephew?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        SecurityQuestion::create(
            array(
                'question' => "What is the first name of your favorite aunt?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        SecurityQuestion::create(
            array(
                'question' => "Where did you spend your honeymoon?",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );
    }
}
