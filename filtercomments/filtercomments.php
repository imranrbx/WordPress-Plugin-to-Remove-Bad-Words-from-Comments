<?php
/*
    Plugin Name: Filter bad Words in comments
    plugin URI: http://fiverr.com/itsmeleo
    Author: Imran Qasim
    Author URI: http://fiverr.com/itsmeleo
    Description: Simple Plugin to Filter Bad Comments from Admin menu
*/
add_action( 'admin_menu', 'xs__add_admin_menu' );
add_action( 'admin_init', 'xs__settings_init' );


function xs__add_admin_menu(  ) { 
    add_menu_page('filtercomments', 'Filter Comments', 'manage_options', 'filtercomments', 'xs__options_page' );
}


function xs__settings_init(  ) { 

    register_setting( 'xs_filterCommentsPage', 'xs__settings' );

    add_settings_section(
        'xs__xs_filterCommentsPage_section', 
        __( 'use this box to enter Bad words from your comments', 'textdomain' ), 
        'xs__settings_section_callback', 
        'xs_filterCommentsPage'
    );

    add_settings_field( 
        'xs_filterComments_text', 
        __( 'Type the words separate by | sign', 'textdomain' ), 
        'xs_filterComments_text_render', 
        'xs_filterCommentsPage', 
        'xs__xs_filterCommentsPage_section' 
    );


}
function xs_filterComments_text_render(  ) { 

    $options = get_option( 'xs__settings' );
    $placeholder ="";
    if(isset($options['xs_filterComments_text']) and empty(trim($options['xs_filterComments_text']))){
        $placeholder = "sex|fuck";
    }
    ?>
    <style>
     div#filter_wrap{
        max-width:400px;
     }
        .badword_filter{
            width:100%;
        }
    </style>
    <div id="filter_wrap">
     <input type="text" class="badword_filter" name='xs__settings[xs_filterComments_text]' placeholder="<?php echo $placeholder; ?>" value="<?php if(isset($options['xs_filterComments_text'])) echo $options['xs_filterComments_text']; ?>"> 
    </div>
    <?php

}


function xs__settings_section_callback(  ) { 

    echo __( 'Bad Filter Comments is a small snippets which helps you to remove bad words from your comments', 'textdomain' );

}


function xs__options_page(  ) { 

    ?>
    <form action='options.php' method='post'>

        <h2>Filter Bad Words from Comments</h2>

        <?php
        settings_fields( 'xs_filterCommentsPage' );
        do_settings_sections( 'xs_filterCommentsPage' );
        submit_button();
        ?>

    </form>
    <?php

}
function xs_filterComments_from_list($comments){
     $options = get_option( 'xs__settings' );
     $chracter = "*";
     $new_comments="";
     if(isset($options['xs_filterComments_text']) AND !empty(trim($options['xs_filterComments_text'])) ) {
             $bad_words = explode("|", trim($options['xs_filterComments_text']));             
     }else{
        $bad_words = "";
         $new_comments = $comments;
     }

     if(is_array($bad_words)){

       foreach ($bad_words as $key => $value) {
                $length = strlen($value);
                $new_comments = str_ireplace($bad_words, str_repeat($chracter, $length), $comments);

        }
     }
     return $new_comments;
}
add_filter('comment_text', 'xs_filterComments_from_list');
?>