<?php

    function style ($filename) {
        return "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" 
                      href=\"".SERVER_CSS."$filename.css\">\n";
    }

    function script ($filename) {
        return "<script type=\"text/javascript\" 
                        src=\"".SERVER_JS."$filename.js\">
                </script>";
    }

?>