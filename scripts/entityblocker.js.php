<?php
$replacement = PluginUihacksEntityblockerrule::getApplicableReplacement();


if($replacement !== false) {
?>

//<script>

Ext.onReady(function() {
	var form = document.querySelector('form[name="form_ticket"]') ||
			document.querySelector('form[name="helpdeskform"]');

	// Cache tout ce qui est dans la form
	for(i=0 ; i<form.childNodes.length ; i++) {
		form.childNodes[i].setAttribute('style', 'display:none');
	}

	// ajoute le message
	var div = document.createElement('div');
	div.innerHTML = '<?php echo addslashes($replacement);?>';
	form.appendChild(div);
});

// </script>

<?php 
}
?>