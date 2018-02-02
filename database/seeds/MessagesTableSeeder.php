<?php

use Illuminate\Database\Seeder;
use App\Storage\Messenger\Thread;
use App\Storage\Offer\Offer;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Carbon\Carbon;

class MessagesTableSeeder extends Seeder
{

    public function defaultEmail()
    {
        return GeneralTableSeeder::theDefaultEmail();
    }

    public function dummyMessages()
    {

        foreach($this->defaultEmail() as $email)
        {
            $salesRepsUser = App\Storage\User\User::where('email', $email)->first();   
            $salesRep = $salesRepsUser->salesRep()->first();
            $customers = $salesRep->customers()->get();

            foreach($customers as $customer)
            {
                $customerUser = $customer->user()->first();

                foreach(config('lbt.message_types') as $msg_type => $lbl)
                {
                    $thread = null;
                    $faker  = \Faker\Factory::create();
                    $new    = true;

                    if($msg_type == 'note'){
                        $thread = Thread::where('type', 'note')
                            ->whereHas('participants', function($q) use($salesRepsUser){
                                $q->where('user_id', $salesRepsUser->id);
                            })
                            ->whereHas('participants', function($q) use($customerUser){
                                $q->where('user_id', $customerUser->id);
                            })->first();

                        if($thread){
                            $new = false;
                        }
                    }

                    if($new){
                        $isYesterday = rand(0,1) == 1;
                        $thread = Thread::create(
                            [
                                'subject'   => $faker->words(2, true),
                                'type'      => $msg_type,
                                'created_at'=> ($isYesterday ? Carbon::yesterday() : Carbon::now() )
                            ]
                        );   
                    }                 
                    
                    if(in_array($msg_type, ['appt', 'price', 'info'])){
                        /**
                         * Set offer
                         */
                        $offer = Offer::orderByRaw("RAND()")->first();
                        $thread->setOffer($offer->id);
                        $thread->save();
                    }

                    $userIds = [$salesRepsUser->id, $customerUser->id];
                        shuffle($userIds);

                    foreach($userIds as $i){

                        Message::create(
                            [
                                'thread_id' => $thread->id,
                                'user_id'   => $i,
                                'body'      => $faker->sentences(4, true),
                            ]
                        );

                    }

                    if($new){

                        foreach($userIds as $i){
                            // Participant
                            $isRead = rand(0,1) == 1;

                            if($isRead){
                                Participant::create(
                                    [
                                        'thread_id' => $thread->id,
                                        'user_id'   => $i,
                                        'last_read' => new Carbon
                                    ]);                                
                            }else{
                                Participant::create(
                                    [
                                        'thread_id' => $thread->id,
                                        'user_id'   => $i
                                    ]);                                
                            }

                        }
                    }
                }              
            }
        }        

    }    

    public function run()
    {
    	$this->dummyMessages();
    }
}