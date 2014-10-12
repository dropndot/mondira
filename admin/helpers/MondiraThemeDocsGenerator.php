<?php
if(!class_exists('MondiraThemeDocsGenerator')){
    class MondiraThemeDocsGenerator {
	    var $title;
	    var $docs;
	    var $config;
        var $theme_slug;
	    function MondiraThemeDocsGenerator( $config, $theme_slug, $title, $docs ) {
		    $this->config = $config;
            $this->theme_slug = $theme_slug;
            $this->title = $title;
            $this->docs = $docs;
		    $this->render();
	    }
	    
	    function render() {
		    echo '<div class="wrap mondira-docs-page">';
		    echo '<div id="icon-'.$this->theme_slug.'" class="icon32 icon32-posts-'.$this->theme_slug.'"><br></div><h2>'.$this->title.'</h2>';
		    
		    echo '<div id="mondira-docs-tabs"><ul class="mondira-docs-tabs">';
		    foreach($this->docs as $docs) {
			    echo '<li><a href="#'.$docs['section'].'">'.$docs['name'].'</a><span></span></li>';
		    }
		    echo '</ul>';
		    foreach($this->docs as $docs) {
			    $this->renderSection($docs['section']);
		    }
		    echo '<div class="clear"></div>';
		    echo '</div>';
		    echo '</div>';
	    }
	    
	    function renderSection($section) {
		    echo '<div id="'.$section.'" class="block">';
            if(file_exists(THEME_DOCS.'/'.$section.'.php'))
                include THEME_DOCS.'/'.$section.'.php';
            else if(file_exists(FRAMEWORK_ADMIN_DOCS.'/'.$section.'.php'))
		        include FRAMEWORK_ADMIN_DOCS.'/'.$section.'.php';
		    echo '<div class="clear"></div>';
		    echo '</div>';
	    }
    }
}
