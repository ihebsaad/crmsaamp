
@extends('layouts.back')

 @section('content')

<?php

?>

						<div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">

                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tests</h6>
                                </div>
                                <div class="card-body">

                                    <form action="{{route('send_sms')}}" method="post">
                                        @csrf
                                        <input type="tel" name="phone" placeholder="Numéro de téléphone" class="form-control" style="width:200px" required>
                                        <button   type="submit" class="pull-right btn btn-primary btn-icon-split ml-20  mt-20   mb-20" >
                                        <span class="icon text-white-50">
                                                <i class="fas fa-sms"></i>
                                            </span>
                                        <span style="width:200px;padding-top:6px"   >Envoyer le code</span>
                                        </button>
                                    </form>

                                </div>
                            </div>



                        </div>

                    </div>

@endsection
