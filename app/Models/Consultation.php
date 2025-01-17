<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

		'user',
		'app',
		'page',
    ];

		// Relation pour la catégorie parente
		public function user()
		{
			return $this->belongsTo(User::class,'user','id');
		}
    }
