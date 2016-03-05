<?php
$jsRules = PluginUihacksFormeditrule::makeJsRules();

if(!empty($jsRules)) {
?>

//<script>
$(function() {
   var rules = <?php echo json_encode($jsRules);?>;
   var disableButtonCSS = {
         'cursor': 'not-allowed',
         'color': '#b0b0b0',
         'background-image': 'linear-gradient(to bottom, #d0d0d0, #c0c0c0)',
         'text-shadow': 'unset',
         'border': '1px solid #b0b0b0',
   };

   rules.forEach(function(rule) {
      var changed = false;

      function modifyClosure() {
         setTimeout(function() {
            if(changed) return;
            if(modifyInput(rule.selector, rule.tooltip, rule.disabled)) {
               //TODO on pourrait faire des event à chaque ajax
               console.log('success', rule.selector);
               changed = true;
            } else {
               console.log('fail', rule.selector);
            }
         }, 100);
      }

      $(document).ajaxComplete(modifyClosure);
      modifyClosure();
   });

   function modifyInput(selector, tooltip, disabled) {
      elements = $(selector).each(function(i, el) {

         $(el).attr('title', tooltip);
         if(disabled) {
            $(el).prop('disabled', true)
               .css('cursor', 'not-allowed');
         }

         if($('#s2id_'+el.id).length) {
            $('#s2id_'+el.id+' a')
               .css('cursor', 'not-allowed')
               .attr('title', tooltip);
         } else if(/hiddendate(\d+)/.test(el.id)) {
            $(el).prop('disabled', disabled);
            $('#'+el.id.replace('hiddendate', 'showdate'))
               .prop('disabled', disabled)
               .css('cursor', 'not-allowed')
               .attr('title', tooltip);
            $(el).closest('td').find('img')
               .off('click')
               .css('cursor', 'not-allowed')
               .attr('title', tooltip);
         } else if(el.tagName === 'A' && disabled) { // il s'agit d'un lien
            $(el)
               .removeAttr('href')
               .removeAttr('onclick')
               .off('click');

            if($(el).hasClass('vsubmit')) {
               $(el).css(disableButtonCSS);
            }
         } else if(el.tagName === 'INPUT' && el.type === 'submit' && disabled) {
            $(el).css(disableButtonStyle);
         }
      });

      return elements.length > 0;
   }


});

// </script>

<?php
}
?>
