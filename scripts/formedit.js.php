<?php
include ("../../../inc/includes.php");
header("Content-type: application/javascript");

$jsRules = PluginUihacksFormeditrule::makeJsRules();

if(!empty($jsRules)) {
?>

//<script>

$(function() {
    var rules = <?php echo json_encode($jsRules);?>;
    var disableButtonStyle = 'cursor: not-allowed; color: #b0b0b0; background-image: linear-gradient(to bottom, #d0d0d0, #c0c0c0); text-shadow: unset; border: 1px solid #b0b0b0;';

    rules.forEach(function(rule) {
        doUntilSuccess(function() {
            //console.log(rule);
            return modify(rule.selector, rule.tooltip, rule.disabled);
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

    function modify(selector, tooltip, disable) {
        var success = false;
        $(selector).each(function(i, el) {
            success = true;
            el = $(el);

            var elForTooltip;
            if(/showdate(\d+)/.test(el.attr('id'))) {
                // il s'agit d'une date
                elForTooltip = modifyDate(el, tooltip, disable);
            } else if(el.prop('tagName') === 'A') {
                // il s'agit d'un lien
                elForTooltip = modifyLink(el, tooltip, disable);
            } else if(el.prop('tagName') === 'SELECT') {
                // Select (avec potentiellement champs de recherche)
                elForTooltip = modifySelect(el, tooltip, disable);
            } else if(el.prop('tagName') === 'INPUT') {
                // input ou bouton de soumission
                elForTooltip = modifyInput(el, tooltip, disable);
            } else {
               elForTooltip = modifyOthers(el, tooltip, disable);
            }

            if(!elForTooltip) {
                success=false;
            } else if(/^hidden/.test(disable)) {
                switch(disable) {
                    case 'hidden':
                        elForTooltip.hide();
                        break;
                    case 'hiddenS':
                        elForTooltip.parent().children().hide();
                        break;
                    case 'hiddenP1':
                        elForTooltip.parent().hide();
                        break;
                    case 'hiddenP2':
                        elForTooltip.parent().parent().hide();
                        break;
                    case 'hiddenP3':
                        elForTooltip.parent().parent().parent().hide();
                        break;
                }
            } else if(tooltip) {
               elForTooltip.attr('title', tooltip);
            }
        });

        return success;
    }

    function modifyDate(el, tooltip, disable) {
        var parent = el.parent(), tmp;

        var num = el.attr('id').match(/showdate(\d+)/)[1];
          var eldate = parent.find('#date'+num+'-date'),
              eltime = parent.find('#date'+num+'-time');
          if(!eldate || !eltime) return null;

        // Si on le modifie, on crée un wrapper qui permettra de manipuler l'ensemble des champs facilement
        tmp = $('<span/>');
        tmp.append(parent.children());
        parent.append(tmp);
        el = tmp;

        switch(disable) {
            case 'disabled':
                eldate.attr('disabled', '');
                  eltime.attr('disabled', '');
                  if(tmp = eldate.parent().find('img'))
                      tmp.remove();
                  if(tmp = eltime.parent().find('img'))
                      tmp.remove();
                break;
            case 'totext':
                el.html(eldate.val() + '&nbsp;' + eltime.val());
                break;
        }

        return el;
    }

    function modifyLink(el, tooltip, disable) {
        switch(disable) {
            case 'disabled':
                el.removeAttr('onclick');
                el.removeAttr('href');
                if(el.hasClass('vsubmit')) {
                    el.attr('style', disableButtonStyle);
                }
                break;
            case 'totext':
                var tmp = $('<span/>');
                tmp.html(el.html());
                tmp.insertBefore(el);
                el.remove();
                el = tmp;
                break;
        }
        return el;
    }

    function modifyInput(el, tooltip, disable) {
       switch(disable) {
           case 'disabled':
               el.attr('disabled', '');
               if(el.attr('type') === 'submit') {
                      // si c'est un bouton on lui donne le look bouton désactivé
                      el.attr('style', disableButtonStyle);
                 }
               break;
           case 'totext':
              var tmp = $('<span/>');
              tmp.html(el.val());
              tmp.insertBefore(el);
              el.remove();
              el = tmp;
              break;
       }
       return el;
    }

    function modifySelect(el, tooltip, disable) {
        // on retire (s'il existe) le champ de recherche associé au dropdown
        var reg = new RegExp('dropdown_'+el.prop('name')+'(\\d+)', '');
        if(disable !== 'no' && reg.test(el.prop('id'))) {
            el.parent().find('#search_'+reg.exec(el.prop('id'))[1]).remove();
        }

       switch(disable) {
           case 'disabled':
               el.attr('disabled', '');
               break;
           case 'totext':
               var tmp = $('<span/>');
               tmp.html(el.find('option[selected]').html());
               tmp.insertBefore(el);
               el.remove();
               el = tmp;
               break;
       }
       return el;
    }

    function modifyOthers(el, tooltip, disable) {
       switch(disable) {
           case 'disabled':
               el.attr('disabled', '');
               break;
       }
       return el;
    }


});

// </script>

<?php
}
?>
