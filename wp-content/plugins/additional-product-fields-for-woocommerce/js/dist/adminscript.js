jQuery(function(){
    jQuery("#woocommerce-order-items").on('click','.rn-delete-order-item',function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        jQuery(e.currentTarget).parent().find('.rnDeletedInput').val(1);
        jQuery(".button.calculate-action").trigger("click");
    })
});