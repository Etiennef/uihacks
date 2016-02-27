<?php
$jsRules = PluginUihacksFormeditrule::makeJsRules();

if(!empty($jsRules)) {
?>

//<script>
Ext.onReady(function() {
	var rules = <?php echo json_encode($jsRules);?>;
	var disableButtonStyle = 'cursor: not-allowed; color: #b0b0b0; background-image: linear-gradient(to bottom, #d0d0d0, #c0c0c0); text-shadow: unset; border: 1px solid #b0b0b0;';
	
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

			if(tooltip) { 
				el.setAttribute('title', tooltip);
			}
			
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
			} else if(el.tagName === 'A') { // il s'agit d'un lien
				if(disable) {
					el.removeAttribute('onclick');
					el.removeAttribute('href');

					// Si c'est un bouton, on lui donne le look bouton désactivé
					if(el.classList.contains('vsubmit')) {
						el.setAttribute('style', disableButtonStyle);
					}
				}
			} else if(disable) {
				el.setAttribute('disabled', '');
				
				if(el.tagName === 'SELECT' && el.name) {
					// on retire (s'il existe) le champ de recherche associé au dropdown
					if(el.id.match('dropdown_'+el.name+'(\\d+)')) {
						var search = document.querySelector('#search_'+el.id.match('dropdown_'+el.name+'(\\d+)')[1]);
						if(search) search.remove();
					}
				} else if(el.tagName === 'INPUT' && el.type === 'submit') {
					// si c'est un bouton on lui donne le look bouton désactivé
					el.setAttribute('style', disableButtonStyle);
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
