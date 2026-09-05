<div class="container">
    <div class="content">

        <?php
        $menuMain = $_SESSION['renderMenu']['menuMainView'];
        $menuSecond = $_SESSION['renderMenu']['menuSecondView'];

        foreach ($menuMain as $key => $value) {
        ?>
            <div class="cardModules">
                <div class="contentModule">
                    <a href="<?php echo $value['url']; ?>" class="linkIcon">
                        <i class="material-icons green-text text-darken-2"><?php echo $value['icono']; ?>
                        </i>
                    </a>
                    <div class="nameModule"> <?php echo $value['titleModule']; ?>
                    </div>
                </div>
            </div>
        <?php } ?>




    </div>
</div>