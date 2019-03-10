<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Conduit :: Watchdog login</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php echo style ("login"); ?>

        <!--<script src="node_modules/requirejs/require.js"></script>-->
        <?php echo script ("require.2.3.6"); ?>
        <?php echo script ("ui"); ?>

        <script>loadContext (["common", "popup", "login"]);</script>
    </head>

    <body>
        <div class="popup-layer" id="popup"></div>

        <div class="login-form">
            <h3 class="login-form-header">
                Watchdog missed you ...
            </h3>
            <p class="login-form-line">
                <label class="login-form-placeholder">
                    <input id="login" placeholder="." type="text" />
                    <span class="placeholder">Login</span>
                </label>
            </p>
            <p class="login-form-line">
                <label class="login-form-placeholder">
                    <input id="password" placeholder="." type="password" />
                    <span class="placeholder">Password</span>
                </label>
            </p>
            <p class="login-form-line">
                <button id="signin" onclick="__page_context.attemptLogin ()">
                    Make attempt
                </button>
            </p>
        </div>
    </body>
</html>