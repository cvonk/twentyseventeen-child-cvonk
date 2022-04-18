console.log("js/category.js");

var cvonk_multicat_js_run = true;

// Adds a Theme Option, that let you pick categories to show as thumbnails intead of cards in category view.
//
// see: https://themefoundation.com/wordpress-theme-customizer/
//      https://themefoundation.com/customizer-multiple-category-control/
//      https://wordpress.stackexchange.com/questions/41548/get-categories-hierarchical-order-like-wp-list-categories-with-name-slug-li
//
window.addEventListener("load", function() {
    
    // keep code from running twice due to live preview window.load firing in addition to the main customizer window.
    if( true == cvonk_multicat_js_run ) {
        cvonk_multicat_js_run = false;
    } else {
        return;
    }
    console.log("category.js load");

    // used to get the current value of our hidden field
    var api = wp.customize;
    
    // checks/unchecks category checkboxes based on saved data
    jQuery('.cvonk-hidden-categories').each(function(){
    
        var id = jQuery(this).prop('id');
        var categoryString = api.instance(id).get();
        var categoryArray = categoryString.split(',');
    
        jQuery('#' + id).closest('li').find('.cvonk-category-checkbox').each(function() {
    
            var elementID = jQuery(this).prop('id').split('-');
    
            if( jQuery.inArray( elementID[1], categoryArray ) < 0 ) {
                jQuery(this).prop('checked', false);
            } else {
                jQuery(this).prop('checked', true);
            }
        });     
    });
    
    // listen to checkboxes
    jQuery('.cvonk-category-checkbox').live('change', function(){
    
        var id = jQuery(this).closest('li').find('.cvonk-hidden-categories').prop('id');
        var elementID = jQuery(this).prop('id').split('-');
    
        if( jQuery(this).prop('checked' ) == true ) {
            addCategory(elementID[1], id);
        } else {
            removeCategory(elementID[1], id);
        }
    
    });
    
    // add category ID to hidden input
    function addCategory( catID, controlID ) {
    
        var categoryString = api.instance(controlID).get();
        var categoryArray = categoryString.split(',');
    
        if ( '' == categoryString ) {
            var delimiter = '';
        } else {
            var delimiter = ',';
        }
	// update hidden field value
        if( jQuery.inArray( catID, categoryArray ) < 0 ) {
            api.instance(controlID).set( categoryString + delimiter + catID );
        }
    }
    
    // remove category ID from hidden input
    function removeCategory( catID, controlID ) {
    
        var categoryString = api.instance(controlID).get();
        var categoryArray = categoryString.split(',');
        var catIndex = jQuery.inArray( catID, categoryArray );
    
        if( catIndex >= 0 ) {
    
            categoryArray.splice(catIndex, 1);  // remove element from array
    
            // creates new category string based on remaining array elements
            var newCategoryString = '';
            jQuery.each( categoryArray, function() {
                if ( '' == newCategoryString ) {
                    var delimiter = '';
                } else {
                    var delimiter = ',';
                }
                newCategoryString = newCategoryString + delimiter + this;
            });
    
            // updates hidden field value
            api.instance(controlID).set( newCategoryString );
        }
    }
 }, false);
