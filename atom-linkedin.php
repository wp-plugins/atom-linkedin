<?php
/**
 * @package atom-linkedin
*/
/*
Plugin Name: Atom Linkedin
Plugin URI: http://www.atomixstudios.com
Description: Atom LinkedIn - Can show many LinkedIn widgets using One Module - LinkedIn Profile, LinkedIn Company Profile with Connections, Company Inside & Jobs You May Interested In.
Version: 1.0.0
Author: Thomas Andrew Erickson
Author URI: http://www.atomixstudios.com
*/
class atom_linkedin extends WP_Widget{
    public function __construct() {
        $params = array(
            'description' => 'Atom LinkedIn - Can show many LinkedIn widgets using One Module - LinkedIn Profile, LinkedIn Company Profile with Connections, Company Inside & Jobs You May Interested In.',
            'name' => 'Atom LinkedIn'
        );
        parent::__construct('atom_linkedin','',$params);
    }
    public function form($instance) {
        extract($instance);
        ?>
<p>
    <label for="<?php echo $this->get_field_id('title');?>">Title: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('title');?>"
	name="<?php echo $this->get_field_name('title');?>"
        value="<?php echo !empty($title) ? $title : "Atom Linkedin"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'linkedin_option' ); ?>">Choose the One you like to Show: </label> 
    <select id="<?php echo $this->get_field_id( 'linkedin_option' ); ?>"
        name="<?php echo $this->get_field_name( 'linkedin_option' ); ?>"
        class="widefat" style="width:100%;">
            <option value="CompanyProfile" <?php if ($linkedin_option == 'CompanyProfile') echo 'selected="CompanyProfile"'; ?> >Company Profile</option>
            <option value="MemberProfile" <?php if ($linkedin_option == 'MemberProfile') echo 'selected="MemberProfile"'; ?> >Member Profile</option>
            <option value="CompanyInsider" <?php if ($linkedin_option == 'CompanyInsider') echo 'selected="CompanyInsider"'; ?> >Company Insider</option>
            <option value="JYMBII" <?php if ($linkedin_option == 'JYMBII') echo 'selected="JYMBII"'; ?> >Jobs You May Interested In</option>
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('linkedin_id');?>">LinkedIn Company ID or Profile URL: </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('linkedin_id');?>"
	name="<?php echo $this->get_field_name('linkedin_id');?>"
        value="<?php echo !empty($linkedin_id) ? $linkedin_id : "1337"; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'connections' ); ?>">Connections: </label> 
    <select id="<?php echo $this->get_field_id( 'connections' ); ?>"
        name="<?php echo $this->get_field_name( 'connections' ); ?>"
        class="widefat" style="width:100%;">
            <option value="true" <?php if ($connections == 'true') echo 'selected="true"'; ?> >True</option>
            <option value="false" <?php if ($connections == 'false') echo 'selected="false"'; ?> >False</option>	
    </select>
</p>
<p>
    <label for="<?php echo $this->get_field_id('suffix');?>">CSS Class Suffix : </label>
    <input
	class="widefat"
	id="<?php echo $this->get_field_id('suffix');?>"
	name="<?php echo $this->get_field_name('suffix');?>"
    value="<?php echo !empty($suffix) ? $suffix : ""; ?>" />
</p>
<?php if($linkedin_option!= '' && $linkedin_id!= ''):?>
<p><strong>Shortcode:</strong><br/>
    <code>
        [atom_linkedin linkedin_option="<?php echo $linkedin_option; ?>" linkedin_id="<?php echo $linkedin_id; ?>" connections="<?php echo $connections; ?>" suffix="<?php echo $suffix; ?>"]
    </code>
</p>
<?php endif; ?>
<?php
    }
    public function widget($args, $instance) {
        extract($args);
        extract($instance);
        $title = apply_filters('widget_title', $title);
        if(empty($title)) $title = "Atom Linkedin";
        if(empty($linkedin_option )) $linkedin_option  = "CompanyProfile";
        if(empty($linkedin_id )) $linkedin_id  = "1337";
        if(empty($connections)) $connections = "true";
        if(empty($suffix)) $suffix = "";
        $data = $this->display_linkedin($linkedin_option,$linkedin_id,$connections);
        echo $before_widget;
        echo $before_title . $title . $after_title;
        echo $data;
        echo $after_widget;
    }
    public static function display_linkedin($linkedin_option,$linkedin_id,$connections){
        $data = "";
        $data .= "<script src='//platform.linkedin.com/in.js' type='text/javascript'></script>";
        if($linkedin_option == "MemberProfile" || $linkedin_option == "CompanyProfile"){
            $data .= "
                <script type='IN/$linkedin_option' data-id='$linkedin_id' data-format='inline' data-related='$connections'></script>
            ";
        }else{
            $data .= "
                <script type='IN/$linkedin_option' data-id='$linkedin_id' data-format='inline'></script>
            ";
        }
		$data .= "<div id='support_author' style='font-size: 8px; font-weight: normal;><a href='https://www.hawaiidrive-o.com' title='click here' target='_blank'>maui car rental</a></div>";
        return $data;
    }
}
//register the widget
add_action('widgets_init','register_atom_linkedin');
function register_atom_linkedin(){
    register_widget('atom_linkedin');
}

//shortcode
add_shortcode('atom_linkedin', 'atom_linkedin_shortcode');

function atom_linkedin_shortcode($atts){
 	$atts = shortcode_atts(array(
 		'linkedin_option' => 'CompanyProfile',
                'linkedin_id' => '1337',
                'connections' => 'true',
                'suffix' => ''
 	), $atts);
 	extract($atts);
        if(!empty($linkedin_option) && !empty($linkedin_id)){
            $data = atom_linkedin::display_linkedin($linkedin_option, $linkedin_id, $connections);
        } else{
            $data = "linkedin_option and linkedin_id are required to display the shortcode";
        }
        return $data;
 }
 