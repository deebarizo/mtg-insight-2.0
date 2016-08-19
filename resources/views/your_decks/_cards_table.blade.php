<div class="row">

	<div class="col-lg-3">

		<h4>Cards</h4>

		<table id="cards" class="table table-striped table-bordered table-hover table-condensed">
			<thead>
				<tr>
					<th>Name</th>
					<th>Add</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($cards as $card)
					<?php 
						$cardNameNoApostrophe = preg_replace('/\'/', '', $card->name); 

						$manaCostHtml = getManaSymbols($card->mana_cost);
					?>

					<tr class="card-row"
						data-card-id="{{ $card->id }}"
						data-card-mana-cost="{{ $card->mana_cost }}"
						data-card-f-mana-cost="{{ $card->f_mana_cost }}"
						data-card-mana-cost-html='{!! $manaCostHtml !!}'
						data-card-mana-sources="{{ $card->mana_sources }}"
						data-card-name="{{ $card->name }}"
						data-card-f-cost="{{ $card->f_cost }}"
						data-card-img-source="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
						<td>
							<a class="card-name" target="_blank" href="/cards/{{ $card->id }}">{{ $card->name }}</a>
							<div style="display: none" class="tool-tip-card-image">
								<img width="223" height="311" src="/files/card_images/{{ $card->sets_cards[0]->set->code }}/{{ $cardNameNoApostrophe }}.png">
							</div>
						</td>
						<td>
							<a class="add-card md" href="" style="margin-right: 5px">
								<div class="icon plus md">
									<span class="glyphicon glyphicon-plus"></span>
								</div>
							</a>
							<a class="add-card sb" href="">
								<div class="icon plus sb">
									<span class="glyphicon glyphicon-plus"></span>
								</div>
							</a>
						</td>
					</tr>	
				@endforeach
			</tbody>
		</table>

	</div>