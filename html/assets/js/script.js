function calcPrice(obj, id, originalPrice, ajax=false)
{
	var qty = obj.value;

	var pHT = originalPrice;

	pHT = (pHT * qty);
	var pTTC =  pHT * 1.2;

	document.getElementById('PHT_'+id).innerHTML = String(pHT.toFixed(2)).replace('.', ',')+"€";
	document.getElementById('PTTC_'+id).innerHTML = String(pTTC.toFixed(2)).replace('.', ',')+"€";
}

function getProductsModal(title, img, content, price, id) {
	$('#modal-message').removeAttr('class').text('');

	$('#modal-title').text(title);
	$('#modal-body-img').attr('src', img).attr('alt', title);
	$('#modal-body').text(content);
	$('#modal-body-price').text(price+'€');
	$('#product_id').attr('onclick', 'addToCart('+id+')');
}