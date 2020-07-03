<?php
$iphoneTheme 		= get_option('iphone_theme');
$ipadTheme			= get_option('ipad_theme');
$androidTheme		= get_option('android_theme');
$androidTabTheme	= get_option('android_tab_theme');
$blackberryTheme	= get_option('blackberry_theme');
$windowsTheme		= get_option('windows_theme');
$operaTheme			= get_option('opera_theme');
$palmTheme			= get_option('parm_os_theme');
$otherTheme			= get_option('other_theme');	

$themeList 			= custom_get_themelist();
$themeNames 		= array_keys($themeList); 
$defaultTheme 		= wp_get_theme();
$defaultTheme 		= $defaultTheme->Name;
natcasesort($themeNames);
?>

<table class="wp-list-table widefat fixed bookmarks">
        <thead>
            <tr>
                <th>Select Theme For Devices</th>
            </tr>
        </thead>
        <tbody>
        <tr>
          <td>
    
    <table class="form-table">
         <tr valign="top">
        <th scope="row">iPhone/iPod Touch Theme:</th>
        <td>
        	<select name="iphone_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($iphoneTheme == $themeName) || (($iphoneTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo '<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row">iPad Theme</th>
        <td>
        	<select name="ipad_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($ipadTheme == $themeName) || (($ipadTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Android Theme</th>
        <td>
        	<select name="android_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($androidTheme == $themeName) || (($androidTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
		
        <tr valign="top">
        <th scope="row">Android Tab Theme</th>
        <td>
        	<select name="android_tab_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($androidTabTheme == $themeName) || (($androidTabTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Blackberry Theme</th>
        <td>
        	<select name="blackberry_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($blackberryTheme == $themeName) || (($blackberryTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
        
        
        <tr valign="top">
        <th scope="row">Windows Mobile Theme</th>
        <td>
        	<select name="windows_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($windowsTheme == $themeName) || (($windowsTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Opera Mini Theme</th>
        <td>
        	<select name="opera_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($operaTheme == $themeName) || (($operaTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Palm Os Theme</th>
        <td>
        	<select name="parm_os_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($palmTheme == $themeName) || (($palmTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Other Mobile Device Theme</th>
        <td>
        	<select name="other_theme">
			 <?php 
              foreach ($themeNames as $themeName) {              
                  if (($otherTheme == $themeName) || (($otherTheme == '') && ($themeName == $defaultTheme))) {
                      echo '<option value="' . $themeName . '" selected="selected">' . htmlspecialchars($themeName) . '</option>';
                  } else {
                      echo'<option value="' . $themeName . '">' . htmlspecialchars($themeName) . '</option>';
                  }
              }
             ?>
        	</select>
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