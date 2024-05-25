<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Client;


class ClientsController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware(['auth']);
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */



	public function create()
	{
		$clients = Client::where('latitude','<>','')->get();
		return view('clients.create',compact('clients'));
	}

	public function search()
	{
		$clients = Client::where('latitude','<>','')->get();
		return view('clients.search',compact('clients'));
	}



} // end class
