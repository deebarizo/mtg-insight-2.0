<?php 

	$cardNameNoApostrophe = preg_replace('/\'/', '', $copy->name); 

	$manaCost = getManaSymbols($copy->mana_cost);
?>

<tr class="copy-row {{ $copy->role }}"
	data-copy-role="{{ $copy->role }}"
	data-copy-name="{{ $copy->name }}"
	data-copy-quantity="{{ $copy->quantity }}"
	data-copy-card-id="{{ $copy->card_id }}"
	data-copy-f-mana-cost="{{ $copy->f_mana_cost }}"
	data-copy-f-cost="{{ $copy->f_cost }}"
	data-copy-mana-sources="{{ $copy->mana_sources }}">

		<td class="quantity">{{ $copy->quantity }}</td>
		<td class="copy-mana-cost-html">{!! $manaCost !!}</td>
		<td class="copy-card-name">
			<a class="card-name" target="_blank" href="/cards/{{ $copy->card_id }}">{{ $copy->name }}</a>
			<div style="display: none" class="tool-tip-card-image">
				<img width="223" height="311" src="/files/card_images/{{ $copy->code }}/{{ $cardNameNoApostrophe }}.png">
			</div>
		</td>
		<td class="copy-f-cost">{{ $copy->f_cost }}</td>
		<td><a class="add-card {{ $copy->role }}" href="" style="margin-right: 5px"><div class="icon plus {{ $copy->role }}"><span class="glyphicon glyphicon-plus"></span></div></a><a class="remove-card {{ $copy->role }}" href=""><div class="icon minus"><span class="glyphicon glyphicon-minus"></span></div></a></td>
</tr>

