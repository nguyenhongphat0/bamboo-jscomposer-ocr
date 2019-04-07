<script type="text/javascript">
    var setScript = function(){
        var urls = [
                    "//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js",
                    "<?php echo $this->_path; ?>assets/lib/ace-builds/src-min-noconflict/ace.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-tools.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-atts.js",
                    "<?php echo $this->_path; ?>assets/js/backend/media-editor.js",
                    "<?php echo $this->_path; ?>assets/lib/autosuggest/jquery.autoSuggest.js",
                    "<?php echo $this->_path; ?>assets/lib/vc_chart/jquery.vc_chart.js",
                    "<?php echo $this->_path; ?>assets/js/editors/panels.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-storage.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-models.js",
                    "<?php echo $this->_path; ?>assets/js/select2.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-view.js",
                    "<?php echo $this->_path; ?>assets/js/backend/composer-custom-views.js",
                    "<?php echo $this->_path; ?>assets/js/backend/deprecated.js",
                    "<?php echo $this->_path; ?>assets/lib/vc_carousel/js/transition.js",
                    "<?php echo $this->_path; ?>assets/lib/vc_carousel/js/vc_carousel.js",
                    "<?php echo $this->_path; ?>assets/lib/progress-circle/ProgressCircle.js"
                   ];
        var script = '';
        for (var i = 0; i < urls.length; i++) {
            script = document.createElement('script');
            script.src = urls[i];
            document.body.appendChild(script);
        }
    }
    setTimeout(function() { setScript(); }, 3000);
</script>