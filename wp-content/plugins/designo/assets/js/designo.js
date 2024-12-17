function goToPersonalizePage(variable, product_id, url, template_id) {
    if (jQuery('button#personalize_btn.disabled').length == 0) {
        //get variation id
        var variation_id = jQuery('.variation_id').val();

        var element = jQuery("#options_65c5cc61a211e");
        if (element.length > 0) {
            var quantity = jQuery("select[name='options[65c5cc61a211e]']").find("option:selected").text();
        } else {
            var quantity = jQuery('[name="quantity"]').val();    
        }
        
        var custom = jQuery('.cart').serialize();
        var redirect_url = url + '/w2p/' + '?product_id=' + product_id + '&variation_id=' + variation_id + '&quantity=' + quantity+'&'+custom;
        if(template_id) {
            redirect_url += "&template_id=" + template_id;
        }
        window.location.href = redirect_url;
    } else {
        return false;
    }
}

function goToQuickeditPage(variable, product_id, url) {
    if (jQuery('button#quickedit_btn.disabled').length == 0) {
        debugger;
        //get variation id
        var variation_id = jQuery('.variation_id').val();
        var element = jQuery("#options_65c5cc61a211e");
        if (element.length > 0) {
            var quantity = jQuery("select[name='options[65c5cc61a211e]']").find("option:selected").text();
        } else {
            var quantity = jQuery('[name="quantity"]').val();    
        }
        // redirect url
        var custom = jQuery('.cart').serialize();
        var redirect_url = url + '/w2p/' + '?quickedit=1&product_id=' + product_id + '&variation_id=' + variation_id + '&quantity=' + quantity+'&'+custom;
        // redirect
        window.location.href = redirect_url;
    } else {
        return false;
    }
}

window.addEventListener("message", function(event) {
    if(event.data.action === "browse_template") {
        let personaliseBtn = document.getElementById("personalize_btn");
        let onclick = personaliseBtn.getAttribute("onclick").split('(')[1];
        onclick = onclick.split(')')[0];
        onclick = onclick.split(',');
        onclick[0] = onclick[0].replaceAll("'", "");
        onclick[1] = onclick[1].replaceAll("'", "");
        onclick[2] = onclick[2].replaceAll("'", "");
        if(personaliseBtn) {
            goToPersonalizePage(onclick[0], onclick[1], onclick[2], event.data.template_id);
        }
    }
});

function openTemplate(variable, product_id, url) {
    debugger;
    var variation_id = jQuery('.variation_id').val();
    var qty = jQuery('[name="quantity"]').val();
    var url = url +'&variation_id=' + variation_id +'&qty=' + qty;

    if (jQuery('button#browsetemplate_btn.disabled').length == 0) {
        let browseTemplateDiv = document.querySelector('.browse-template-modal');
        if(browseTemplateDiv == null) {

            browseTemplateDiv = document.createElement('div');
            document.body.append(browseTemplateDiv);
            browseTemplateDiv.className = 'browse-template-modal';
            
    
            browseTemplateDiv.innerHTML = `<div id="modalDialog" class="modal">
            <div class="modal-content animate-top">
                <div class="modal-body">
                    <button type="button" class="close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <iframe id="browse-template-iframe" name="Design N Buy" src="${url}" frameborder="0" scrolling="yes"></iframe>
                </div>
            </div>
        </div>`
    
        } else {
            jQuery('#browse-template-iframe').attr('src',url);
        } 
        var modal = jQuery('#modalDialog');
    
        modal.show();
        
        var span = jQuery(".close");
        // When the user clicks on <span> (x), close the modal
        span.on('click', function() {
            modal.fadeOut();
        });
    
        // When the user clicks anywhere outside of the modal, close it
        jQuery('body').bind('click', function(e){
            if(jQuery(e.target).hasClass("modal")){
                modal.fadeOut();
            }
        });

    } else {
        return false;
    }
}

function openFileUpload(){
    if (jQuery('button#uploadfiles.disabled').length == 0) {
        document.querySelector("file-upload").open();
    } else {
        return false;
    }
}