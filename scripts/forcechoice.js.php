<?php
$categoryMessage = __('Vous devez d\'abord choisir le type de ticket', 'uihacks');
$sumbitErrorMessage = __('Le type et/ou l\'urgence du ticket n\'a pas été sélectionnée');

$config = PluginUihacksForcechoiceconfig::getConfigValues();

if($config['is_activated'] && 
		isset($_SERVER['HTTP_REFERER']) &&
		preg_match("/^([^\/]*\/)*(helpdesk\.public\.php\?create_ticket=1|tracking\.injector\.php|ticket\.form\.php(?!(\?id=\d+))).*/",
			$_SERVER['HTTP_REFERER'])
		) {
?>
	
//<script>
Ext.onReady(function() {
	var form = document.querySelector('form[name="form_ticket"]') ||
			document.querySelector('form[name="helpdeskform"]');
	var dd_type = form.querySelector('select[name="type"]');
	var dd_urgency = form.querySelector('select[name="urgency"]');
	var uihState = parseQuery().uihstate || '00';

	if(uihState.charAt(0) === '0') {
		var html = dd_type.innerHTML;
		html = html.replace('selected=""', '');
		html = '<option value="-1" selected="">-----</option>'+html;
		dd_type.innerHTML = html;
		 
		form.querySelector('select[name="itilcategories_id"]').parentNode.parentNode
				.innerHTML = '<?php echo addslashes($config['category_msg']);?>';
	}

	if(uihState.charAt(1) === '0') {
		var html = dd_urgency.innerHTML;
		html = html.replace('selected=""', '');
		html = '<option value="-1" selected="">-----</option>'+html;
		dd_urgency.innerHTML = html;
	}

	var allSubmitterOnChange = form.querySelectorAll('[onchange="submit()"]');
	for(var i=0 ; i<allSubmitterOnChange.length ; i++) {
		allSubmitterOnChange[i].onchange = uihacksOnchange;
	}
	form.onsubmit = uihacksSubmit;

	/****************************
	 *		Fonctions			*
	 ****************************/

	function makeUihacksState() {
		return ((dd_type.value == -1)?'0':'1') +
		((dd_urgency.value == -1)?'0':'1');
	}

	function uihacksOnchange() {
		form.action = form.action + (/\?/.test(form.action) ? '&' : '?') + 'uihstate=' + makeUihacksState();
		form.submit();
	}

	function uihacksSubmit() {
		if(makeUihacksState() === '11') {
			return true;
		} else {
			alert('<?php echo addslashes($config['bad_submit_msg']);?>');
			return false;
		}
	}

	function parseQuery() {
		var result = {},
		tmp = [];
		location.search.substr(1).split("&").forEach(function (item) {
			tmp = item.split("=");
			result[tmp[0]] = tmp[1];
		});
		return result;
	}
});
//</script>
<?php 
}
?>