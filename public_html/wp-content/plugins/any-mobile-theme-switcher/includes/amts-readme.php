</form>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th>Read Me Please</th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
        <h3>Theme Switch Shortcode</h3>
        <p>
        Use the following shortcode <strong>[show_theme_switch_link]</strong> in templates to show the theme switch link.    
        <br/>Example: <strong>&lt;?php echo do_shortcode('[show_theme_switch_link]'); ?&gt;</strong>
        <br/><br/>
        You can also add Switch Mobile Theme link to your Menus from Custom Links section under Appearance > Menus.<br />
        Example:<br />
        Url : <strong><?php bloginfo('url'); ?>/?<?php echo $amts_force_param; ?>=desktop</strong> (For Mobile Theme)<br/>
        Url : <strong><?php bloginfo('url'); ?>/?<?php echo $amts_force_param; ?>=mobile</strong>  (For Desktop Theme)<br/>
        Label :  As you wish :)    
        </p>
</td></tr></tbody></table>