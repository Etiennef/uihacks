<?php
$jsRules = PluginUihacksTooltiprule::makeJsRules();

if(!empty($jsRules)) {
?>

//<script>
Ext.onReady(function() {
	var rules = <?php echo json_encode($jsRules);?>;
	
	rules.forEach(function(rule) {
		doUntilSuccess(function() {
			//console.log('selector', rule.selector);
			return modifyInput(rule.selector, rule.tooltip, rule.disabled);
		}, 50, 30);
	});

	function doUntilSuccess(func, interval, retry) {
		var i=0;
		var t = setInterval(function() {
			i++;
			if(i > retry && retry!=0 || func()) {
				clearInterval(t);
			}
		}, interval);
	}

	function modifyInput(selector, tooltip, disable) {
		elements = document.querySelectorAll(selector);

		for(var i = 0 ; i < elements.length ; i++) {
			var el = elements[i];
			
			if(el.id.match(/showdate(\d+)/)) {
				// il s'agit d'une date
				var num = el.id.match(/showdate(\d+)/)[1];
				var eldate = document.querySelector('#date'+num+'-date'), 
					eltime = document.querySelector('#date'+num+'-time');
				if(!eldate || !eltime) return false;
				
				if(disable) {
					eldate.setAttribute('disabled', '');
					eltime.setAttribute('disabled', '');
					if(eldate.parentNode.querySelector('img'))
						eldate.parentNode.querySelector('img').remove();
					if(eltime.parentNode.querySelector('img'))
						eltime.parentNode.querySelector('img').remove();
				}
				if(tooltip) {
					el.parentNode.setAttribute('title', tooltip);
				}
			} else if(el.tagName === 'A' && disable) {
				// il s'agit d'un lien (éventuellement en forme de bouton) à désactiver
				var p = document.createElement('p');
				p.innerHTML = el.innerHTML;
				el.parentNode.insertBefore(p, el);
				el.remove();
				if(tooltip) {
					p.setAttribute('title', tooltip);
				}
			} else {
				if(el.tagName === 'SELECT') {
					// on retire (s'il existe) le champ de recherche associé au dropdown
					//TODO : ne marche pas forcément
					var name = selector.match('select\\[name="(.*)"\\]')[1];

					if(el.id.match('dropdown_'+name+'(\\d+)')) {
						var search = document.querySelector('#search_'+el.id.match('dropdown_'+name+'(\\d+)')[1]);
						if(search) search.remove();
					}
				}
				
				if(disable) {
					el.setAttribute('disabled', '');
				}
				if(tooltip) {
					el.setAttribute('title', tooltip);
				}
			}
		}

		return elements.length > 0;
	}
	
	
});

// </script>

<?php 
}
?>
