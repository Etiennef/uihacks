<?php
include ("../../../inc/includes.php");

//change mimetype
header("Content-type: application/javascript");

//if(!isset($_SERVER['HTTP_REFERER'])) 
//	$_SERVER['HTTP_REFERER'] = "http://glpi0848.localhost/front/ticket.form.php?id=2016021801"; //TODO


$inputModifRules = PluginUihacksInputmodifrule::getRulesValues();
$optionModifRules = PluginUihacksOptionmodifrule::getRulesValues();

function loglog($var) {
	echo 'console.log('.json_encode($var).');';
}


$referer = preg_replace("/([^\/]*\/)*([^\/]*)/", "$2", $_SERVER['HTTP_REFERER']);
$allRules = array_merge($inputModifRules, $optionModifRules);
$jsRules = array();

// On passe en revue les règle et on les décline en règles basiques pour le js
// On en profite pour filtrer les règles sur le profil et l'URL
foreach($allRules as $rule) {
	if(!in_array($_SESSION['glpiactiveprofile']['id'], $rule['profiles']))
		continue;
	
	$patterns = PluginUihacksOptionmodifrule::getPageFormIndex();
	foreach ($rule['pageforms'] as $pageform) {
		foreach ($patterns[$pageform]['patterns'] as $url=>$form) {
			if(preg_match($url, $referer)) {
				$tmp = array(
					'form' => $form,
					'name' => $rule['name'],
					'tooltip' => $rule['tooltip'],
					'disabled' => ($rule['disabled']?true:false)
				);
				if(isset($rule['option'])) $tmp['option'] = $rule['option'];
				$jsRules[] = $tmp;
			}
		}
	}	
}

?>
// <script>
Ext.onReady(function() {
	var rules = <?php echo json_encode($jsRules);?>;
	
	rules.forEach(function(rule) {
		if(typeof rule.option === 'undefined') {
			doUntilSuccess(function() {return modifyInput(rule.form, rule.name, rule.disabled, rule.tooltip);}, 50, 30);
		} else {
			doUntilSuccess(function() {return modifyOption(rule.form, rule.name, rule.option, rule.disabled, rule.tooltip);}, 50, 30);
		}
	});

	function doUntilSuccess(func, interval, retry) {
		var i=0;
		var t = setInterval(function() {
			if(func() || i >= retry && retry!=0) {
				clearInterval(t);
			}
			i++;
		}, interval);
	}
	
	function modifyInput(form, name, disable, tooltip) {
		var input = document.querySelector('form[name="'+form+'"] [name="'+name+'"]');
		if(!input) 
			return false;
		
		
		if(input.id.match(/showdate(\d+)/)) {
			// il s'agit d'une date
			
			var num = input.id.match(/showdate(\d+)/)[1];
			var inputdate = document.querySelector('#date'+num+'-date'), 
				inputtime = document.querySelector('#date'+num+'-time');
			if(!inputdate || !inputtime) return false;
			
			if(disable) {
				inputdate.setAttribute('disabled', '');
				inputtime.setAttribute('disabled', '');
				if(inputdate.parentNode.querySelector('img'))
					inputdate.parentNode.querySelector('img').remove();
				if(inputtime.parentNode.querySelector('img'))
					inputtime.parentNode.querySelector('img').remove();
			}
			if(tooltip) {
				input.parentNode.setAttribute('title', tooltip);
			}
		} else {
			if(input.tagName === 'SELECT') {
				// dans le cas d'un select, on retire (s'il existe) le champ de recherche
				var search = document.querySelector('#search_'+input.id.match('dropdown_'+name+'(\\d+)')[1]);
				if(search) search.remove();
			}
			
			if(disable) {
				input.setAttribute('disabled', '');
			}
			if(tooltip) {
				input.setAttribute('title', tooltip);
			}
		}

		return true;
	}
	
	function modifyOption(form, name, value, disable, tooltip) {
		var opt = document.querySelector('form[name="'+form+'"] [name="'+name+'"] option[value="'+value+'"]');
		if(!opt) 
			return false;
		
		if(disable) {
			opt.setAttribute('disabled', '');
		}
		if(tooltip) {
			opt.setAttribute('title', tooltip);
		}
		return true;
	}
	
});

// </script>

