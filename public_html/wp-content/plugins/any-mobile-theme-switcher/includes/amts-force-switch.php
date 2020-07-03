<?php
$mobileThemeText		= get_option('mobile_view_theme_link_text');
$desktopThemeText		= get_option('desktop_view_theme_link_text');	
$desktopSwitchLink		= get_option('show_switch_link_for_desktop');
$forcedLayoutExpireTime	= get_option('forced_layout_expire_time');

if (empty($mobileThemeText)){
	update_option('mobile_view_theme_link_text', 'Switch To Mobile Version');
	$mobileThemeText	= get_option('mobile_view_theme_link_text');
}

if (empty($desktopThemeText)){
	update_option('desktop_view_theme_link_text', 'Switch To Desktop Version');
	$desktopThemeText	= get_option('desktop_view_theme_link_text');	
}

if (empty($desktopSwitchLink)){
	update_option('show_switch_link_for_desktop', 'no');
	$desktopSwitchLink	= get_option('show_switch_link_for_desktop');	
}

if (empty($forcedLayoutExpireTime)){
	update_option('forced_layout_expire_time', '0');
	$forcedLayoutExpireTime	= get_option('forced_layout_expire_time');	
}

?>
<table class="wp-list-table widefat fixed bookmarks">
            <thead>
                <tr>
                    <th>Other Settings (Optional)</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>
   
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Remember forced layout till</th>
        <td>
        	<select name="forced_layout_expire_time" style="width:300px">
            	<option value="0" <?php echo $forcedLayoutExpireTime == '0'?'selected="selected"':''; ?>>Browser is closed</option>
                <option value="3600" <?php echo $forcedLayoutExpireTime == '3600'?'selected="selected"':''; ?>>1 hour</option>
                <option value="21600" <?php echo $forcedLayoutExpireTime == '21600'?'selected="selected"':''; ?>>6 hours</option>
                <option value="43200" <?php echo $forcedLayoutExpireTime == '43200'?'selected="selected"':''; ?>>12 hours</option>
                <option value="86400" <?php echo $forcedLayoutExpireTime == '86400'?'selected="selected"':''; ?>>1 day</option>
                <option value="604800" <?php echo $forcedLayoutExpireTime == '604800'?'selected="selected"':''; ?>>1 week</option>
                <option value="2419200" <?php echo $forcedLayoutExpireTime == '2419200'?'selected="selected"':''; ?>>1 month</option>
            </select>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Switch Mobile Theme Link Text</th>
        <td>
        <input name="mobile_view_theme_link_text" style="width:300px;"  value="<?php echo $mobileThemeText; ?>" type="text" />
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Switch Desktop Theme Link Text</th>
        <td>
        <input name="desktop_view_theme_link_text" style="width:300px;" value="<?php echo $desktopThemeText; ?>" type="text" />
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Do you want to show Switch Mobile Theme link even the vistor is viewing from desktop ?</th>
        <td>
        	<input name="show_switch_link_for_desktop" type="radio" value="yes" <?php echo $desktopSwitchLink == 'yes'?'checked="checked"':''; ?> /> Yes &nbsp;&nbsp;&nbsp;
            <input name="show_switch_link_for_desktop" type="radio" value="no" <?php echo $desktopSwitchLink == 'no'?'checked="checked"':''; ?> /> No <br/><span class="description">Normally, it is <b>NO</b>. It is usually useless to force the visitor to switch to mobile theme when s/he is in desktop.</span>
        </td>
        </tr>
        
       <tr valign="top">
        <th scope="row">&nbsp;</th>
        <td>
        	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </td>
        </tr>
        
    </table>
    
    <br/>
    
</td></tr></tbody></table>

<br/>