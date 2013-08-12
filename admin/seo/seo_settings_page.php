<?php 
	include_once (PARENT_DIR . '/admin/seo/sitemap-generator.php');

	if (!function_exists('seo_settings_page')) {
		function seo_settings_page () { ?>
			<div id="optionsframework-wrap" class="wrap">
				<div id="icon-generic" class="icon32"><br></div><h2><?php echo 'SEO'//theme_locals("data_management"); ?></h2>
				<h2 class="nav-tab-wrapper">
					<!--a class="nav-tab" title="General settings" href="#general"><?php echo 'General'//theme_locals("data_management"); ?></a-->
					<a class="nav-tab" title="Settings sitemap XML" href="#sitemap-xml"><?php echo 'Sitemap XML'//theme_locals("data_management"); ?></a>
					<!--a id="" class="nav-tab nav-tab-active" title="" href="">Breadcrumbs</a-->
				</h2>
				<div id="optionsframework-metabox">
					<div id="optionsframework" class="postbox store-holder">
						<form id='options'>
							<!--div id="general" class="group">
								<h3><?php echo 'General'//theme_locals("data_management"); ?></h3>
							</div-->
							<div id="sitemap-xml" class="group">
								<h3><?php echo 'Sitemap XML'//theme_locals("data_management"); ?></h3>
								<div class="section">
									<h4 class="heading">Post types settings</h4>
									<div class="option">
										<div class="controls">
											<header class="group_options">
												<div class="unitu">Include post types.</div>
												<div class="unitu">Priority.</div>
												<div class="unitu">Change freq.</div>
											</header>
											<?php
												$post_types = array_merge(array('test' => '', 'page' => '', 'post' => '', 'services' => '', 'portfolio' => '', 'slider' => '', 'team' => '', 'testi' => '', 'faq' => ''), get_post_types(array('public'   => true, '_builtin' => false), 'objects', 'or'));
												$priority_array = array(0, 0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1);
												$changefreq_array = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');
												unset($post_types['wpcf7_contact_form'], $post_types['optionsframework'], $post_types['attachment']);
												
												foreach( $post_types as $post_type ) {
													if(!empty($post_type)){
														$checked = (get_option('checked_'.$post_type->name) == "on") ? 'checked' : '' ;
														echo '<div class="group_options"><div class="unitu"><input id="'.$post_type->name.'" class="checkbox of-input" type="checkbox" '.$checked.' name="checked_'.$post_type->name.'">';
														echo '<label class="explain checkbox_label" for="'.$post_type->name.'">'.$post_type->labels->name.'</label></div>';
														echo '<div class="unitu"><select class="of-typography-character" name="priority_'.$post_type->name.'">';
															foreach( $priority_array as $priority ) {
																$selected = get_option('priority_'.$post_type->name) == $priority ? 'selected' : '' ;
																echo '<option value="'.$priority.'" '.$selected.'>'.$priority.'</option>';
															}
														echo '</select></div>';
														echo '<div class="unitu"><select class="of-typography-character" name="changefreq_'.$post_type->name.'">';
															foreach( $changefreq_array as $changefreq ) {
																$selected = get_option('changefreq_'.$post_type->name) == $changefreq ? 'selected' : '' ;
																echo '<option value="'.$changefreq.'" '.$selected.'>'.$changefreq.'</option>';
															}
														echo '</select></div></div>';
													}
												}
											?>
										</div>
										<div class="explain">...</div>
									</div>
								</div>
								<div class="section">
									<h4 class="heading">Send</h4>
									<div class="option">
										<div class="controls">
										<?php 
										$checked = (get_option('google_ping') == "on") ? 'checked' : '' ;
											echo '<input id="google" class="checkbox of-input" type="checkbox" '.$checked.' name="google_ping"><label class="explain checkbox_label" for="google"><span class="icon_googl"></span>Google</label><br>';
										$checked = (get_option('yandex_ping') == "on") ? 'checked' : '' ;
											echo '<input id="yandex" class="checkbox of-input" type="checkbox" '.$checked.' name="yandex_ping"><label class="explain checkbox_label" for="yandex"><span class="icon_yandex"></span>Yandex</label><br>';
										$checked = (get_option('yahoo_ping') == "on") ? 'checked' : '' ;
											echo '<input id="yahoo" class="checkbox of-input" type="checkbox" '.$checked.' name="yahoo_ping"><label class="explain checkbox_label" for="yahoo"><span class="icon_yahoo"></span>Yahoo!</label><br>';
										$checked = (get_option('bing_ping') == "on") ? 'checked' : '' ;
											echo '<input id="bing" class="checkbox of-input" type="checkbox" '.$checked.' name="bing_ping"><label class="explain checkbox_label" for="bing"><span class="icon_bing"></span>Bing</label><br>';
										$checked = (get_option('ask_ping') == "on") ? 'checked' : '' ;
											echo '<input id="ask" class="checkbox of-input" type="checkbox" '.$checked.' name="ask_ping"><label class="explain checkbox_label" for="ask"><span class="icon_ask"></span>Ask.com</label><br>';
										?>
										</div>
										<div class="explain">...</div>
									</div>
								</div>
								<div class="section">
									<hr>
									<div class="button_wrapper" id="generator_sitemap">
										<a href="#" id="generate_sitemap" class="button-primary">Generate Sitemap</a>
									</div>
								</div>
							</div>
							<div id="optionsframework-submit" class="clearfix">
								<div class='button_wrapper fright'>
									<input type="submit" class="button-primary" name="save_options" value="Save Options">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php
		}
	}
	add_action('wp_ajax_save_options', 'cherry_save_options');
	if (!function_exists('cherry_save_options')) {
		function cherry_save_options() {
			$post_data = $_POST;
			unset($post_data['action']);
			foreach ($post_data as $key => $val) {
				update_option($key, $val);
			}
			exit; 
		} 
	}

	// page java script
	add_action('admin_footer', 'page_script');
	if (!function_exists('page_script')) {
		function page_script() {
			?>
			<script>
				function add_click_ajax(objects, data){
					jQuery.ajax({
						url:ajaxurl,
						type: "POST",
						data: data,
						beforeSend: function() {
							objects.css({visibility: "hidden"}).parent().css({background: 'url("images/wpspin_light.gif") center no-repeat', boxShadow: 'inset 0px 0px 10px 5px #E5E5E5', borderRadius: 3});
						},
						success: function(d) {
							//console.log(d);
							objects.parent().css({background: 'url("images/yes.png") center no-repeat'});
							setTimeout(function() {
								objects.css({visibility: "visible"}).parent().css({background: "none", boxShadow: 'none'});
							}, 1000)
						}
					});
				}
				jQuery('#optionsframework-submit input[name="save_options"]').click(function(){
					var data = {action: 'save_options'};
					jQuery('#sitemap-xml input, #sitemap-xml select').each(function(){
						var item = jQuery(this),
							value = item.val();
						if(value=='on' && item[0].checked == false){
							value='off';
						}
						data[item[0].name] = value;
					});
					add_click_ajax(jQuery(this), data);
					return !1;
				})
				jQuery('#generate_sitemap').click(function(){
					var sitemap_data = {action: 'generate_sitemap'};
					jQuery('#sitemap-xml input:checked, #sitemap-xml select').each(function(){
						if(jQuery(this).parents('.group_options').find('input:checked')[0] || jQuery(this).context.checked){
							sitemap_data[jQuery(this).attr('name')] = jQuery(this).val();
						}
					})
					add_click_ajax(jQuery(this), sitemap_data);
					return !1;
				})	
			</script>
			<?php 
		}
	}
?>