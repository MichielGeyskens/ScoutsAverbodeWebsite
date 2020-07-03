<?php
//Include the comment-upload handler plugin
//ECU Code require_once('easy-comment-uploads/main.php');
//Include the diggstyle pagination
require_once('_diggstyle.php');


/**
  * This is the only function you need to generate a WP-Viper Guestbook - simply
  * "echo vgb_GetGuestbook()" in any of your templates.
  * 
  * Note: Templates that use this guestbook should NOT call comments_template().
  * Note: Requires the_post() to've been called already, i.e. we're in The Loop.
  * Note: See the $defaults array for available options.
  */
function vgb_GetGuestbook( $opts=array() )
{
    $defaults = array(
        'entriesPerPg' => 10,       //Number if entries to show per page
        'reverseOrder' => false,    //Reverse the order of entries (oldest first)
        'showBrowsers' => true,     //Show browser/OS icons in guestbook entries  
        'showFlags'    => true,     //Show national flags in guestbook entries (REQUIRES OZH IP2NATION)
        'hideCred'     => false,    //Omit "Powered by WP-ViperGB" (please don't though :))
        'showCredLink' => false,    //Include a link to the project page in the "Powered by WP-ViperGP" (would be appreciated :))
        'disallowAnon' => false,	//Don't allow anonymous signatures (aka only logged-in users can sign)
        'diggPagination'=>false		//Use Digg-style pagination (rather than this plugin's original style)
         );       
    $opts = wp_parse_args( $opts, $defaults );

    if( vgb_is_listing_pg() )   return vgb_get_listing_pg($opts);
    else                        return vgb_get_sign_pg($opts);
}


/********************************************************************************/
/******************************IMPLEMENTATION************************************/
/********************************************************************************/


//PHP Arguments
define('VB_SIGN_PG_ARG', 'sign');       //"Sign" page (vs "Listing" page)
define('VB_PAGED_ARG', 'cpage');        //Paged Comments pagenumber


/**
  * Return true if this is the LISTING page, false if it's the SIGN page
  */
function vgb_is_listing_pg()
{
    return !isset($_REQUEST[VB_SIGN_PG_ARG]);
}


/**
  * Return the URL to the plugin directory, with trailing slash
  */
function vgb_get_data_url()
{
    return plugins_url(dirname(plugin_basename(__FILE__))) . '/';
}


/**
  * Return the current page number (in listing view)
  */
function vgb_get_current_page_num()
{
    return max(get_query_var(VB_PAGED_ARG), 1);
}


/**
  * Get the header: Show Guestbook | Sign Guestbook, and *maybe* paged nav links
  */
function vgb_get_header( $itemTotal, $entriesPerPg, $diggPagination )
{
    //Comment
    global $vgb_name, $vgb_version;
    $retVal = "<!-- $vgb_name v$vgb_version -->\n";
        
    //Show Guestbook | Sign Guestbook
    $isListingPg = vgb_is_listing_pg();
    $retVal .= '<div id="gbHeader">';
    $retVal .= '<div id="gbNavLinks">';
    if( !$isListingPg ) $retVal .= "<a href=\"".get_permalink()."\">";
    $retVal .= __('Show Guestbook', 'wp-vipergb');
    if( !$isListingPg ) $retVal .= "</a>";
    $retVal .= " | ";
    if( $isListingPg ) $retVal .= "<a href=\"". esc_url(add_query_arg(VB_SIGN_PG_ARG, 1))."\">";
    $retVal .= __('Sign Guestbook', 'wp-vipergb');
    if( $isListingPg ) $retVal .= "</a>";
    $retVal .= "</div>";

	//For Digg-style pagination
	if($diggPagination == 1)
	{
    	$retVal .= '<div id="gbPageLinks">';
    	if ($isListingPg)
	        $retVal .= $itemTotal . ' ' . __('entries','wp-vipergb');
	    $retVal .= "</div>";
	    $retVal .= "</div>";
    }	
	
    //Paged/paginated nav links
    if($isListingPg && $itemTotal > $entriesPerPg)
    {
        $curPage = vgb_get_current_page_num();
        $maxPages = ceil($itemTotal/$entriesPerPg);
        if($diggPagination == 0) $retVal .= '<div id="gbPageLinks">' . __('Page','wp-vipergb') . ': ';
        if( $maxPages > 1 )
        {
        	//Original-style paged nav links
        	if( $diggPagination == 0 )
			{
            	for( $i = 1; $i <= $maxPages; $i++ )
            	{
                	if( $curPage == $i || (!$curPage && $i==1) ) $retVal .= "(" . $i . ") ";
                	else                                         $retVal .= "<a href=\"".esc_url(add_query_arg(VB_PAGED_ARG, $i))."\">$i</a> ";
            	}
			}
			//Digg-style paginated nav links
			else
			{
				//Digg-Style Pagination
            	$retVal .= '<div class="gbPagination">';
            	$retVal .= getPaginationString($curPage, $itemTotal, $entriesPerPg, 1, get_permalink(), '?cpage=');
            	$retVal .= '</div>';
			}
        }
        if($diggPagination == 0) $retVal .= "</div>";
    }
    if($diggPagination == 0) $retVal .= "</div>";
    return $retVal;
}



/*************************************************************************/
/************************Output the LISTINGS PAGE*************************/
/*************************************************************************/
function vgb_get_listing_pg($opts)
{
    //Capture output
    ob_start();
    
    //First, get the comments and make sure we have some
    global $comment, $post;
    $comments = get_comments( array('post_id' => $post->ID, 'order' => ($opts['reverseOrder']?'ASC':'DESC') ) );
    $commentTotal = count($comments);
    
    //Output the header
    echo vgb_get_header($commentTotal, $opts['entriesPerPg'], $opts['diggPagination']);
    
    //Check for "no entries"
    if( $commentTotal == 0 ):
        echo '<div id="gbNoEntriesWrap">' . __('No entries yet', 'wp-vipergb') . '.</div>';
    else:
    
    //Take a SLICE of the comments array corresponding to the current page
    $curPage = vgb_get_current_page_num();
    $comments = array_slice($comments, ($curPage-1)*$opts['entriesPerPg'], $opts['entriesPerPg']);
    $commentCounter = $commentTotal - ($curPage-1)*$opts['entriesPerPg'];
   
    //And output each comment!
    ?>
    <div id="gbEntriesWrap">
    <?php foreach( $comments as $comment ): ?>
    <table class="gbEntry page-nav">
     <tr>
      <td class="gbEntryLeft" rowspan="3">
       <table class="nocellspacing">
        <tr>
         <td class="leftSide"><?php _e('Entry#', 'wp-vipergb')?>:</td>
         <td class="rightSide">
          <?php
              if($opts['reverseOrder'])   echo $commentTotal - ($commentCounter--) + 1;
              else                        echo $commentCounter--;
          ?>
         </td>
        </tr>
        <tr>
         <td class="leftSide vtop"><?php _e('Date', 'wp-vipergb')?>:</td>
         <td class="rightSide">
           <?php echo get_comment_date('l')?><br /><?php echo get_comment_time(__('H:i','wp-vipergb'))?><br /><?php echo get_comment_date(__('m.d.Y','wp-vipergb'))?>
         </td>
        </tr>
       </table>
      </td>
      <td class="gbEntryTop" >
       <div class="gbAuthor">
        <img alt="ip" src="<?php echo vgb_get_data_url()?>img/ip.gif" /> <?php echo apply_filters('vgb_author_name', $comment->comment_author, $comment) ?><?php edit_comment_link('..', '');?>
       </div>
       <div class="gbFlagAndBrowser">
       <?php
        if( $opts['showBrowsers'] )
        {
            if( !function_exists('pri_images_string') ) include_once('browsersniff/browsersniff.php');
            $browser_name= $browser_code= $browser_ver= $os_name= $os_code= $os_ver=$pda_name= $pda_code= $pda_ver= $image= $between=null;
            list( $browser_name, $browser_code, $browser_ver, $os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver ) = pri_detect_browser($comment->comment_agent);
            echo pri_images_string($browser_name, $browser_code, $browser_ver, $os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver, $image, $between);
        }
        if( $opts['showFlags'] && function_exists('wp_ozh_getCountryCode') )
          echo '<img src="' . vgb_get_data_url() . "img/flags/" . wp_ozh_getCountryCode(0, $comment->comment_author_IP).'.png" alt="." title="'.wp_ozh_getCountryName(0, $comment->comment_author_IP).'" />';
       ?>
       </div>
      </td>
     </tr>
     <tr>
      <td class="gbEntryContent">
       <?php
       if( $comment->comment_approved == 1 ) comment_text();
       else                                  echo "<i><b>".__('This entry is awaiting moderation','wp-vipergb')."</b></i>";
       ?>
      </td>
     </tr>
     <tr>
      <td class="gbEntryBottom">
       <?php if( $comment->comment_author_email ): ?>
         <img alt="" src="<?php echo vgb_get_data_url()?>img/email.gif" /> &lt;<?php _e('hidden', 'wp-vipergb')?>&gt;<br />
       <?php endif; ?>
       <?php if( $comment->comment_author_url ): ?>
         <img alt="" src="<?php echo vgb_get_data_url()?>img/home.gif" /> <a href="<?php echo $comment->comment_author_url?>"><?php echo substr($comment->comment_author_url, strpos($comment->comment_author_url, '://')+3)?></a><br />
       <?php endif; ?>
      </td>
     </tr>
    </table>
    <?php endforeach; ?>
    <?php if( !$opts['hideCred'] )
       {
          global $vgb_homepage;
          if( $opts['showCredLink'] )
            echo '<span id="gbCredit">' . __("Powered by", 'wp-vipergb') . ' <a href="'. $vgb_homepage. '">WP-ViperGB</a></span>';
          else
            echo '<span id="gbCredit">' . __("Powered by", 'wp-vipergb') . ' WP-ViperGB</span>';
        }
    ?></div><?php
    
    //if( $commentTotal == 0 ):
    endif;
    
    //Stop capturing output and return
    $output_string=ob_get_contents();
    ob_end_clean();
    return $output_string;
}


/*************************************************************************/
/********************Output the SIGN GUESTBOOK page***********************/
/*************************************************************************/
function vgb_get_sign_pg($opts)
{
    //Get the current user (if logged in)
    $user = wp_get_current_user();
    if ( empty( $user->display_name ) ) $user->display_name=$user->user_login;
        
    //If not, we'll try to use info from the cookie to pre-fill in the fields
    $commenter = wp_get_current_commenter();
    
    //Capture output
    ob_start();
    
    //Output the header
    echo vgb_get_header(0, $opts['entriesPerPg'], $opts['diggPagination']);
    
    //And output the page!
   ?>
   <div id="gbSignWrap" class="page-nav">
    <form action="<?php echo get_option("siteurl")?>/wp-comments-post.php" method="post" id="commentform">
     
     <?php if( $opts['disallowAnon'] && !$user->ID ) : 
     	_e('Sorry, but only registered users are allowed to sign this guestbook. Please create a user account, or login to sign.','wp-vipergb');
	else: ?>
	
     <!-- Name/Email/Homepage section -->
     <table id="gbSignPersonal">
      <tr>
       <td><?php _e('Name', 'wp-vipergb')?>:</td>
       <td>
        <?php if($user->ID):?> <input type="text" name="author" id="author" value="<?php echo $user->display_name?>" disabled="disabled" size="30" maxlength="40" />
        <?php else:         ?> <input type="text" name="author" id="author" value="<?php echo $commenter['comment_author']?>" size="30" maxlength="40" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('(required)', 'wp-vipergb'); ?>
       </td>
      </tr>
      <tr>
       <td><?php _e('Email', 'wp-vipergb')?>:</td>
       <td>
        <?php if($user->ID):?> <input type="text" name="email" id="email" value="<?php echo $user->user_email?>" disabled="disabled" size="30" maxlength="40" />
        <?php else:         ?> <input type="text" name="email" id="email" value="<?php echo $commenter['comment_author_email']?>" size="30" maxlength="40" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('(required)', 'wp-vipergb'); ?>
       </td>
      </tr>
      <tr>
       <td><?php _e('Homepage', 'wp-vipergb')?>:</td>
       <td>
        <?php if($user->ID):?> <input type="text" name="url" id="url" value="<?php echo $user->user_url?>" disabled="disabled" size="30" />
        <?php else:         ?> <input type="text" name="url" id="url" value="<?php echo esc_url($commenter['comment_author_url'])?>" size="30" />
        <?php endif; ?>
        <?php if(!$opts['disallowAnon']) _e('(optional)', 'wp-vipergb'); ?>
       </td>
      </tr> 
     </table>
     <table>
       <tr>
         <td>
           <?php
           remove_action('comment_form', 'show_subscription_checkbox');
           remove_action('comment_form', 'subscribe_reloaded_show');
           remove_action('comment_form', 'jfb_show_comment_button');
           global $post;
           do_action('comment_form', $post->ID);
           ?>
         </td>
       </tr>
     </table>
     <?php if( $user->ID && !$opts['disallowAnon'] ) echo __("*If you'd like to customize these values, please ", 'wp-vipergb') . "<b><a href=\"". wp_logout_url( $_SERVER['REQUEST_URI'] ) . "\">" . __("Logout", 'wp-vipergb') . "</a></b>."; ?>
     <!-- End Name/Email section -->
     
     <!-- Text section -->
     <div id="gbSignText">
       <?php _e('Text', 'wp-vipergb')?>:<br />
       <textarea name="comment" id="comment" rows="12" cols="45"></textarea><br />
       <input style="width:100px;" name="submit" type="submit" id="submit" value="<?php _e('Send', 'wp-vipergb')?>" />
       <input type="hidden" name="comment_post_ID" value="<?php echo $GLOBALS['id']?>" />
       <input type='hidden' name='redirect_to' value='<?php echo htmlspecialchars(get_permalink()) ?>' />
     </div>
     <!-- EndText area section -->
     <?php endif; ?>
    </form>
   </div>
   <?php
   
   //Stop capturing output and return
   $output_string=ob_get_contents();
   ob_end_clean();
   return $output_string;
}

?>