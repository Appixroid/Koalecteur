<!DOCTYPE html>
<?php
    define("ADMIN", true);
    require("../Core.php");
?>
<html>
    <head>
        <title>Koalecteur - Connexion</title>
        <meta charset="utf-8" />

        <style>
            body
            {
                background-color: rgb(121,85,72);
            }

            form
            {
                width: 33%;
                height: 66%;

                margin: auto;
                margin-top: 50vh;

                transform: translateY(-50%);

                background-color: rgb(178,255,89);
                border-radius: 10px;
            }

            fieldset
            {
                font-style: italic;
                color: gray;

                border: 0px white solid;

                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
            }

            label
            {
                font-style: normal;
                color: rgba(0, 0, 0, 0.7);
            }

            input
            {
                background-color: rgba(255, 255, 255, 0);
                border: white 0px solid;
                border-bottom: solid gray 1px;

                margin-bottom: 10%;

                transition: border 0.5s;
            }

            input:active, input:focus
            {
                border-bottom: solid rgb(121,85,72) 1px;
            }

            input[type=submit]
            {
                border-radius: 5px;
                background-color: rgb(121,85,72);
                color: white;
                padding: 4%;
            }

            input[type=submit]:focus, input[type=submit]:active
            {
                background-color:rgb(93,64,55);
            }
        </style>
    </head>

    <body>
        <?php
            $message = "";
            if(isset($_GET['error']))
            {
                switch ($_GET['error'])
                {
                    case '1':
                        $message = Core::getTranslation("unknow_login");;
                        break;

                    case '2':
                        $message = Core::getTranslation("login_before");
                        break;
                }
            }
        ?>
        <div class="error">
            <?php echo $message;?>
        </div
        >
        <form action="index.php" method="POST">
            <fieldset>
                <legend><?php echo Core::getTranslation("connexion");?></legend>

                <label for="login"><?php echo Core::getTranslation("login");?> : </label>
                <br/>
                <input type="text" name="login" placeholder="<?php echo Core::getTranslation("login");?>..." id="login"/>

                <br/>

                <label for="pwd"><?php echo Core::getTranslation("password");?> : </label>
                <br/>
                <input type="password" name="pwd" placeholder="*****" id="pwd" />

                <br/>

                <input type="submit" value="<?php echo Core::getTranslation("connexion");?>" />
            </fieldset>
        </form>
    </body>
</html>
