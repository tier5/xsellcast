<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Storage\Ontraport\OntraportHttpd;
use Mail;

class OntraPortMailController extends Controller
{
    public function index(Request $request)
    {
		$message = array(
		        'html' => '<p>Example HTML content</p>',
		        'text' => 'Example text content',
		        'subject' => 'example subject',
		        'from_email' => 'info@caffeineinteractive.com',
		        'from_name' => 'Example Name',
		        'to' => array(
		            array(
		                'email' => 'info@caffeineinteractive.com',
		                'name' => 'Recipient Name',
		                'type' => 'to'
		            )
		        ),
		        'headers' => array('Reply-To' => 'message.reply@example.com')
		    );

    	$mandrill = new \Mandrill(config('lbt.mandrill_key'));
    	// $send = $mandrill->messages->send($message);
    	dd($send);
		//Mail::send('emails.ontraport.test', [], function ($m) {
		//    $m->from('hello@app.com', 'Foo Bar Your Application');
		//    $m->to('info@caffeineinteractive.com', 'Caffeine Interactive')->subject('Your Reminder!');

		    /**
		     * Manipulate header of mail
		     */
		//	$swiftMessage = $m->getSwiftMessage();
		//	$headers = $swiftMessage->getHeaders();
		//	$headers->addTextHeader('Disposition-Notification-To', '"Caffeine Interactive" <info@caffeineinteractive.com>');
		//});
    }
}
