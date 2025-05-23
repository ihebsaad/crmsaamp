@extends('layouts.back')

@section('content')

<i?php

?>

<style>
    h6 {
        color: black;
        font-weight: bold;
    }

    .table,
    .table td,
    .table th {
        border: none !important;
    }
</style>
<div class="row">

    <div class="col-lg-12 col-sm-12 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row" style="margin:0">
                    <div class="col-md-8 col-sm-12">

                        <h6 class="m-0 font-weight-bold text-primary">{{__('msg.Complaint')}} {{$retour->id}}    
                            @if($retour->idclient > 0)
                            <a href="{{route('fiche',['id'=>$retour->idclient])}}">{{$client->cl_ident}} - {{$client->Nom}}</a>
                            @else
                            {{$retour->cl_id}} - {{$retour->Nom_du_compte}}
                            @endif
                            @if(isset($contact))
                            Contact: {{$contact->Prenom}} {{$contact->Nom}}
                            @endif
                            - {{__('msg.Open date')}}: {{date('d/m/Y', strtotime($retour->Date_ouverture))}}
                        </h6>
                    </div>
                    <div class="col-md-4 col-sm-12 text-right">
                        @if($previousRetour)
                            <a href="{{ route('retours.show', $previousRetour->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        @endif

                        @if($nextRetour)
                            <a href="{{ route('retours.show', $nextRetour->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body" style="min-height:500px">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        @if($retour->Date_cloture!='' )
                            @if($resolutionDelay>0)
                            <div class="text-primary"><i class="fas fa-clock"></i> Résolu en: <b>{{ $resolutionDelay }}</b> jours <i>  </div>
                            @endif
                        @elseif($resolutionDelay>0)
                            <div class="text-primary"><i class="fas fa-clock"></i> Délais de résolution : <b>{{ $resolutionDelay }}</b> jours ouvrés <i> ( {{ date('d/m/Y', strtotime($retour->date_max))}}</i> )</div>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12">
                        @if($isDeadlineExceeded && $retour->Date_cloture=='')
                            <div class="alert alert-danger"     style="display: ruby-text;">
                                <i class="fas fa-exclamation-triangle"></i> 
                                {{ __('msg.Resolution deadline has been exceeded!') }}
                            </div>
                        @endif
                    </div>
                </div>
                <form action="{{ route('retours.update', $retour->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edited_by" value="{{auth()->user()->id}}">

                    <div class="row pt-1 mb-1">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Name">{{__('msg.Reference')}}:</label>
                                <h6>{{$retour->Name}}</h6>
                                <!--<input type="text" id="Name" class="form-control" name="Name"  value="{{$retour->Name}}"><br><br>-->

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Motif_retour">Type de demande:</label>
                                <h6>{{$types[$retour->Type_Demande]}}</h6>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label for="Motif_retour">{{__('msg.Reason for return')}}:</label>
                                <h6>{{$retour->Motif_retour}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Motif_retour">Nature:</label>
                                <h6>{{$natures[$retour->Nature]}}</h6>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            @if(count($files)>0)
                            <label for="Description">{{ __('msg.File(s)') }}:</label><br>
                            <table style="border:none;width:100%">
                                @foreach ($files as $file)
                                <tr>
                                    <td style="border:none;"><label><b class="black mr-2">{{ $file->name }}</b></label></td>
                                    <td style="border:none;"><a href="{{ url('/fichiers/retours/' . $file->name) }}" target="_blank"><img class="view mr-1" title="Visualiser" width="25" src="{{ URL::asset('img/view.png') }}"></a></td>
                                    <td style="border:none;"><a href="{{ url('/fichiers/retours/' . $file->name) }}" download><img class="download mr-1" title="Télécharger" width="25" src="{{ URL::asset('img/download.png') }}"></a></td>
                                    <td style="border:none;">
                                    <td>
                                        <a title="{{__('msg.Delete')}}" onclick="deleteFile('{{ $file->id }}')" href="javascript:void(0);" class="btn btn-danger btn-sm btn-responsive " style="padding:3px!important" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                            <span class="fa fa-fw fa-trash-alt"></span>
                                        </a>
                                    </td>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            @endif
                        </div>


                        <div class="col-md-1 col-sm-12">
                            @if(auth()->id()== $retour->user_id || auth()->user()->user_role==1 || auth()->user()->user_role==2 || auth()->user()->user_role==3 || auth()->user()->user_role== 5  )
                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('retours.destroy', $retour->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                            </a>
                            <div class="clearfix"></div>
                            <button type="submit" class="btn-primary btn btn-sm float-right mt-3 mr-2"><span class="fa fa-fw fa-save"></span>  Enregistrer  </button>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-2">
                            <div class="">
                                <label for="Division">{{__('msg.Division')}}:</label>
                                <h6>{{$retour->Division}}</h6>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-2">
                            <div class="">
                                <label for="Date_ouverture">{{__('msg.Open date')}}:</label>
                                <h6>{{date('d/m/Y', strtotime($retour->Date_ouverture))}}</h6>
                            </div>
                        </div>
--->
                        <div class="col-md-2">
                            <div class="">
                                <label for="Date_cloture">{{__('msg.Closing date')}}:</label>
                                <input type="text" id="Date_cloture" class="form-control datepicker" name="Date_cloture" value="{{$retour->Date_cloture}}"><br><br>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mt-4">
                            <label for="demande_infos"> <input type="checkbox" value="1" name="demande_infos" id="demande_infos" />  <i class="fa fa-envelope"></i>  Demander plus d'informations concernant cette réclamtion</label> 
                            </div>
                        </div>
                        <!-- here
                        <div class="col-md-3">
                            <div class="">
                                <label for="Division">{{__('msg.Customer')}}:</label>
                                @if($retour->idclient > 0)
                                    <h6><a href="{{route('fiche',['id'=>$retour->idclient])}}">{{$retour->cl_id}} - {{$retour->Nom_du_compte}}</a></h6>
                                @else
                                    <h6>{{$retour->cl_id}} - {{$retour->Nom_du_compte}}</h6>
                                @endif
                            </div>
                        </div>
-->
                    </div>


                    <div class="row mt-2">

                        <div class="col-md-2">
                            <div>
                                <label for="Type_retour">{{__('msg.Batch reference')}}:</label>
                                <h6>{{$retour->Ref_produit_lot_commande_facture}}</h6>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="">
                                <label for="Responsable_de_resolution">{{__('msg.Agency')}}:</label>
                                <select id="Responsable_de_resolution" class="form-control" name="Responsable_de_resolution" onchange="check_email()">
                                    <option></option>
                                    @foreach($agences as $agence)
                                    <option @selected($retour->Responsable_de_resolution==$agence->agence_lib) value="{{$agence->agence_lib}}">{{$agence->agence_lib}}</option>
                                    @endforeach
                                </select><br><br>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div>
                                <label for="Type_retour">{{__('msg.Return type')}}:</label><br>
                                <b class="bg-{{$class}}" style="color:white;padding: 5px 10px;border-radius:5px">{{$retour->Type_retour}}</b>
                            </div>
                        </div>

                        <div class="col-md-2" style="font-size:13px">
                            @if($retour->user_id > 0)
                            <?php $creator = \App\Models\User::find($retour->user_id); ?>
                            <i>{{__('msg.Created by')}} :</i><br><b style="color:black">{{$creator->name}} {{$creator->lastname}}</b><br>
                            @endif
                            <br>

                            @if($retour->edited_by > 0)
                            <?php $User = \App\Models\User::find($retour->edited_by); ?>
                            <i>{{__('msg.Last update by')}} :</i><br><b style="color:black">{{$User->name}} {{$User->lastname}}</b>
                            @endif

                        </div>

                        <div class="col-md-2 dep" @if($retour->Responsable_de_resolution!='LIMONEST' ) style="visibility:hidden" @endif>
                            <label for="Department">{{__('msg.Department')}}:</label>
                            <select name="Departement" class="form-control" id="Departement" @if($retour->Responsable_de_resolution!='LIMONEST' ) disabled @endif >
                                <option>Choisissez</option>
                                <option value="FRET">FRET</option>
                                <option value="Laboratoire">Laboratoire</option>
                                <option value="Fonte">Fonte</option>
                                <option value="Production">Production</option>
                                <option value="Qualité">Qualité</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                        </div>

                    </div>


                    <div class="row pt-1">

                        <div class="col-md-12">
                            <div class="">
                                <label for="Details_des_causes">{{__('msg.Details of causes')}}:</label>
                                <textarea id="Details_des_causes" class="form-control" name="Details_des_causes" style="min-height:150px">{{$retour->Details_des_causes}}</textarea><br><br>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="">
                                <label for="Une_reponse_a_ete_apportee_au_client">{{__('msg.Following')}}:</label>
                                <textarea id="Une_reponse_a_ete_apportee_au_client" class="form-control" name="Une_reponse_a_ete_apportee_au_client" style="min-height:150px">{{$retour->Une_reponse_a_ete_apportee_au_client}}</textarea><br><br>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="">
                                <label for="Description_c">{{__('msg.Purpose')}}:</label>
                                <textarea id="Description_c" class="form-control" name="Description_c" style="min-height:150px">{{$retour->Description_c}}</textarea><br><br>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-6">
                            <div class="mt-2">
                                @if(isset($contact))
                                <h4>Contact</h4>
                                <table class="table">
                                <tr><td colspan="2"><i class="fas fa-user mr-2"></i>  {{$contact->Prenom}} {{$contact->Nom}} </td></tr>
                                <tr><td colspan="2"><i class="fas fa-briefcase  mr-2"></i> {{$contact->Title}}</td></tr>
                                <tr><td  ><i class="fas fa-mobile  mr-2"></i> {{$contact->MobilePhone}}</td><td> <i class="fas fa-phone mr-2"></i> {{$contact->Phone}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-envelope  mr-2"></i> {{$contact->Email}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-store mr-2"></i> {{$contact->Compte}}</td></tr>
                                <tr><td colspan="2"><i class="fas fa-info  mr-2"></i> {{$contact->Description}}</td></tr>
                                </table>
                                @endif

                            </div>
                        </div>
                                -->
                    </div>

                    <div class="row pt-1 pb-1">
                        <div class="col-md-12">
                            <div class="">
                                <label for="Nom_offre">{{__('msg.Add files')}}:</label>
                                <input type="file" id="fichier" class="form-control" name="files[]" multiple accept="application/pdf" /><br><br>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="row pt-1 pb-1">

                    </div>



                        <div class="col-md-3">
                            <div class="">
                                <label for="Depot_concerne ">{{__('msg.Deposit concerned')}}:</label>
                                <h6>{{$retour->Depot_concerne}}</h6>
                            </div>

                        </div>


                    <div class="row pt-1">
                        <div class="col-md-12">
                            <button type="submit" class="btn-primary btn float-right">Modifier</button>
                            @if(auth()->user()->user_type=='admin' || auth()->user()->email=='directeur.qualite@saamp.com' || auth()->user()->email=='stephane.hamel@saamp.com')
                            <a title="{{__('msg.Delete')}}" onclick="return confirm('Êtes-vous sûrs ?')" href="{{route('retours.destroy', $retour->id )}}" class="btn btn-danger btn-sm btn-responsive mr-2 float-right" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer">
                                <span class="fa fa-fw fa-trash-alt"></span> {{__('msg.Delete')}}
                            </a>
                            @endif
                        </div>
                    </div>
-->
                </form>

            </div>

        </div>

    </div>


    <script>
        function check_email() {
            let depot = $("#Responsable_de_resolution").val();
            if (depot == 'LIMONEST') {
                //$( "#email_responsable" ).show();
                $("#Departement").css('visibility', 'visible');
                $(".dep").css('visibility', 'visible');
                $("#Departement").attr("disabled", false);
                $("#Departement").attr("required", true);

            } else {
                //$( "#email_responsable" ).hide();
                $("#Departement").css('visibility', 'hidden');
                $(".dep").css('visibility', 'hidden');
                $("#Departement").attr("disabled", true);
                $("#Departement").attr("required", false);
                $("#Departement").val("");
            }
        }

        $(function() {

            $(".datepicker").datepicker({

                altField: "#datepicker",
                closeText: 'Fermer',
                prevText: 'Précédent',
                nextText: 'Suivant',
                currentText: 'Aujourd\'hui',
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
                dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
                dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
                dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                weekHeader: 'Sem.',
                buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
                firstDay: 1,
                dateFormat: "yy-mm-dd",
                minDate: 0
            });

        });


        function deleteFile(fileId) {
            if (confirm('Êtes-vous sûrs ?')) {
                fetch(`{{ url('/files') }}/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Failed to delete file.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("An error occurred while deleting the file.");
                    });
            }
        }
    </script>

    @endsection