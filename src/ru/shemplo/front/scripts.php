<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Conduit :: Scripts</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php echo style ("console"); ?>

        <?php echo script ("require.2.3.6"); ?>
        <?php echo script ("ui"); ?>

        <script>loadContext (["common", "popup", "scripts"], "initScripts");</script>
    </head>

    <body>
        <div class="popup-layer" id="popup"></div>

        <?php require_once ("top_menu.html"); ?>

        <div class="console-columns-block columns-block">
            <div class="console-column">
                <div class="console-column-tile column-tile-border">
                    <h3 class="column-tile-header">
                        Scripts statistics
                    </h3>

                    <div class="columns-block">
                        <div class="console-column">
                            <div class="console-column-tile">
                                <span><b>Runs of scripts:</b></span><br />
                                <span>
                                    <?php
                                        $script_runs_file = "configs/script-runs.info";
                                        $script_runs_info = @file_get_contents ($script_runs_file);
                                        if ($script_runs_info) {
                                            $script_runs_info = explode ("\n", $script_runs_info);
                                            echo ($script_runs_info [0]);
                                        }
                                    ?>
                                    run<i>s</i>
                                </span>
                            </div>
                            <div class="console-column-tile">
                                <span><b>Last run:</b></span><br />
                                <span>
                                    <?php
                                        if ($script_runs_info) {
                                            echo ($script_runs_info [1]);
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="console-column">
                            <div class="console-column-tile">
                                <span><b>Windows scripts:</b></span><br />
                                <span>
                                    <?php
                                        $scripts = total_scripts_by_platform ();
                                        echo $scripts["windows"];
                                    ?>
                                    script<i>s</i>
                                </span>
                            </div>
                            <div class="console-column-tile">
                                <span><b>Linux scripts:</b></span><br />
                                <span>
                                    <?php echo $scripts["linux"]; ?>
                                    script<i>s</i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="console-column-tile column-tile-border">
                    <h3 class="column-tile-header">
                        Upload script
                    </h3>

                    <div class="console-column-tile">
                        <label class="file-upload-label">
                            <input id="scriptFile" type="file" />
                            <span>
                                Select file
                                (<span class="file-upload-selection">
                                    file not selected
                                </span>) 
                            </span>
                        </label>
                        <div>
                            <textarea class="form-textarea"></textarea>
                        </div>
                        <button class="file-upload-button" 
                            onclick="__page_context.uploadScript ()">
                            Upload
                        </button>
                        <span style="margin-left: 0.5em;">
                            Only <b>.cmd</b> and <b>.sh</b> scripts
                        </span>
                    </div>
                </div>
            </div>
            <div class="console-column">
                <div class="console-column-tile column-tile-border">
                    <h3 class="column-tile-header">
                        List of scripts 
                        <span class="linear-preloader" id="scriptsPreloader">
                            <div></div> <div></div> <div></div>
                        </span>
                    </h3>
                </div>
            </div>
            <div class="console-column">

            </div>
        </div>
    </body>
</html>