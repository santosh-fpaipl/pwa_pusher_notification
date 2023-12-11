<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class PushNotification extends Model
	{
    		protected $fillable = [
        		'subscribable_type',
        		'subscribable_id',
        		'endpoint',
        		'public_key',
        		'auth_token',
        		'content_encoding',
    		];

    		public function subscribable()
    	       {
        		return $this->morphTo();
    		}

    		// scope
    		public static function findByEndpoint($endpoint)
    		{
        		return static::where('endpoint', $endpoint)->first();
    		}

	 }