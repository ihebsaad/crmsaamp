@extends('layouts.back')

@section('content')

<?php



?>

<div class="row">

	<div class="col-lg-12 mb-4">

		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="m-0 font-weight-bold text-primary"> </h6>
			</div>
			<div class="card-body">

				<table class="table   mb-40" style="width:100%">
					<thead>
						<tr id="headtable">
							<th class="text-center">{{__('msg.Image')}}</th>
							<th class="text-center">{{__('msg.Reference')}}</th>
							<th class="text-center">{{__('msg.Design')}}</th>
							<th class="text-center  hidemobile">{{__('msg.Measures')}}</th>
							<th class="text-center hidemobile">{{__('msg.Alloy')}}</th>
							<th class="text-center  ">{{__('msg.Weight')}}</th>
							<th class="text-center  hidemobile">{{__('msg.Ordered Qty')}}</th>
							<th class="text-center  ">{{__('msg.Delivered Qty')}}</th>
							<th class="text-center  hidemobile">{{__('msg.Optional Labour')}}</th>
						</tr>
					</thead>
					<tbody>
						@if(is_array($commandes) || is_object($commandes))
						@foreach($commandes as $commande)
						<?php
						$img = '';
						$image = DB::table('photo')->where('photo_id', $commande->photo_id)->first();
						if (isset($image)) {
							$img = trim($image->url);
						}
						?>
						<tr>
							<td class="text-center"> <?php if ($img != '') { ?><img style="max-height:120px;max-width:120px;" src="https://mysaamp.com/images/{{$img}}" class="img-fluid pt-20" alt=""><?php } ?></td>
							<td class="text-center"><?php echo  $commande->ref; ?></td>
							<td class="text-center"><?php echo  $commande->design; ?></td>
							<td class="text-center hidemobile"><?php echo  $commande->mes1 . ' ' . $commande->mes2; ?></td>
							<td class="text-center hidemobile"> <?php echo  $commande->alliage; ?></td>
							<td class="text-center"><?php echo  $commande->poids; ?>g</td>
							<td class="text-center hidemobile"><?php echo  $commande->qte_com; ?></td>
							<td class="text-center "><?php echo  $commande->qte_liv; ?></td>
							<td class="text-center hidemobile"><?php echo  $commande->compl; ?></td>

						</tr>
						@endforeach
						@endif
					</tbody>
				</table>

			</div>
		</div>

	</div>

</div>

@endsection