<?php 
global $ss_settings;
if ( $ss_settings['header_search'] == 2 ) {
?>
	<div class="row">
		<div class="col-md-9">
			<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
			  <div class="input-group input-group-lg">
			    <input type="text" id="autocomplete-ajax" name="s" id="s" class="searchajax search-query form-control" autocomplete="off" placeholder="<?php _e('Find help! Enter search term here.','knowledgepress'); ?>">
			    <span class="input-group-btn">
			      <input type="submit" id="searchsubmit" value="<?php _e('Search', 'knowledgepress'); ?>" class="btn">
			    </span>
			  </div>
			</form>
			<script> _url = '<?php echo home_url(); ?>';</script>		
		</div>
	</div>
<?php } else { ?>
	<div class="row">
		<div class="col-md-9">
			<form role="search" method="get" id="searchform" class="form-search" action="<?php echo home_url('/'); ?>">
			  <div class="input-group input-group-lg">
			    <input type="text" name="s" id="s" class="search-query form-control" autocomplete="off" placeholder="<?php _e('Find help! Enter search term here.','knowledgepress'); ?>">
			    <span class="input-group-btn">
			      <input type="submit" id="searchsubmit" value="<?php _e('Search', 'knowledgepress'); ?>" class="btn">
			    </span>
			  </div>
			</form>
		</div>
	</div>
<?php } ?>
