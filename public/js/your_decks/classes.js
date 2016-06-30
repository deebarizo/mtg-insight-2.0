/****************************************************************************************
CARD
****************************************************************************************/

function Card(id, name, manaCost, manaCostHtml, fCost, imgSource) {

	this.id = id;
	this.name = name;
	this.manaCost = manaCost;
	this.manaCostHtml = manaCostHtml;
	this.fCost = fCost;
	this.imgSource = imgSource;
}


/****************************************************************************************
COPY
****************************************************************************************/

function Copy(quantity, role, card) {

	this.quantity = quantity;
	this.role = role.trim();
	this.card = card;

	this.html = '<tr class="copy-row '+role+'" data-copy-quantity="'+this.quantity+'" data-copy-card-id="'+this.card.id+'" data-copy-mana-cost="'+this.card.manaCost+'" data-copy-role="'+this.role+'" data-card-f-cost="'+this.card.fCost+'"><td class="quantity">'+this.quantity+'<td class="copy-mana-cost-html">'+card.manaCostHtml+'</td><td class="copy-card-name"><a class="card-name" target="_blank" href="/cards/'+this.card.id+'">'+this.card.name+'</a><div style="display: none" class="tool-tip-card-image"><img width="223" height="311" src="'+this.card.imgSource+'"></td><td class="copy-f-cost">'+card.fCost+'</td><td><a class="add-card md" href="" style="margin-right: 5px"><div class="icon plus '+this.role+'"><span class="glyphicon glyphicon-plus"></span></div></a><a class="remove-card '+this.role+'" href=""><div class="icon minus"><span class="glyphicon glyphicon-minus"></span></div></a></td></tr>';
}


/****************************************************************************************
DECKLIST
****************************************************************************************/

function Decklist() {

	this.totals = getDecklistTotals();
}
