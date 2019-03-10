<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Conduit :: Watchdog console</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php echo style ("console"); ?>

        <?php echo script ("require.2.3.6"); ?>
        <?php echo script ("ui"); ?>

        <script>loadContext (["common", "popup"]);</script>
    </head>

    <body>
        <?php require_once ("top_menu.html"); ?>

        <div class="console-columns-block columns-block">
            <div class="console-column">
                <div class="console-column-tile column-tile-border">
                    <h3 class="column-tile-header">
                        System information
                    </h3>

                    <div class="columns-block">
                        <div class="console-column">
                            <div class="console-column-tile">
                                <span><b>Host:</b></span><br />
                                <span>
                                    <?php echo $_SERVER ['HTTP_REFERER'] ?>
                                </span>
                            </div>
                            <div class="console-column-tile">
                                <span><b>Date & time:</b></span><br />
                                <span>
                                    <?php 
                                        $time = $this->context ["time"];
                                        echo date ("d.m.Y H:i:s", $time);
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="console-column">
                            <div class="console-column-tile">
                                <span><b>Server:</b></span><br />
                                <span>
                                    <?php echo $_SERVER ['SERVER_SOFTWARE'] ?>
                                    ( <?php echo $_SERVER ['SERVER_ADDR']
                                          . " : ".$_SERVER ['SERVER_PORT'] ?> )
                                </span>
                            </div>
                            <div class="console-column-tile">
                                <span><b>Operating system:</b></span><br />
                                <span>
                                    <?php echo php_uname ("s"); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="console-column">
                dsf
            </div>
            <div class="console-column">
                dsf
            </div>
        </div>
    </body>
</html>