<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \Validator;
use App\Storage\Media\Media;
use App\Storage\User\User;
use Auth;
use Request;

class ValidatorServiceProvider extends ServiceProvider {

	/**
	* Bootstrap any necessary services.
	*
	* @return void
	*/
	public function boot()
	{
        Validator::extend('comma_max', function ($attribute, $value, $parameters, $validator) {
            $max = $parameters[0];
            $count = count(explode(',', $value));

            return ($count <= $max);
        });	

        Validator::extend('is_media_image', function ($attribute, $value, $parameters, $validator) {

        	$imageCount = Media::whereIn('id', $value)->where('type', 'image')->count();

        	return ($imageCount > 0);
        });	
        
        Validator::extend('is_valid_message_type', function ($attribute, $value, $parameters, $validator) {

        	$keys = array_keys(config('lbt.message_types'));
            
        	return (in_array($value, $keys));
        });	        

        Validator::extend('required_if_message_direct', function ($attribute, $value, $parameters, $validator) {

            return ($value != '');
        });

        Validator::extend('required_if_not_messege_direct', function ($attribute, $value, $parameters, $validator) {

        	//($value != '' && )
            
        	if(!isset($validator->attributes()[$parameters[0]])){
        		return false;
        	}

			$type = $validator->attributes()[$parameters[0]];        	

        	return ($value != '' && $type != 'message');
        });	  

        Validator::extend('in_contact_email_of', function ($attribute, $value, $parameters, $validator) {
            $c = User::where('email', $value)->count();

            return ($c > 0);
        });
        
        /**
         * This is use for validating "hours of operation" field.
         */
        Validator::extend('valid_hoo', function ($attribute, $value, $parameters, $validator) {

            foreach($value as $day => $row)
            {
                if(isset($row['closed'])){
                    continue;
                }

                if($row['from'] == $row['to']){

                    return false;
                }
            }

            return true;
        });

        Validator::extend('password_exist_to_user', function ($attribute, $value, $parameters, $validator) {

            return Auth::attempt(['email' => $parameters[0], 'password' => $value], false, false);
        });      

        /**
         * Check weather a category don't have an offer.
         */
        Validator::extend('category_no_has_offer', function ($attribute, $value, $parameters, $validator) {

            return false;
        });          

        /**
         * Check weather a BA is assigned to prospect.
         */
        Validator::extend('is_salesrep_assign', function ($attribute, $value, $parameters, $validator) {

            return ($parameters[0] == 1);
        });  

	}

	/**
	* Register the service provider.
	*
	* @return void
	*/
	public function register()
	{
   
	
	}

}      