<style>
    body {
        display: flex;
        height: 100vh;
        width: 100vw;
        justify-content: center;
        align-items: center;
        align-content: center;

    }

    .calendar {
        display: flex;
        flex-wrap: wrap-reverse;
        flex-direction: column-reverse;

        height: 120px;
        width: 795px;
        background-color: green;

    }

    .case {
        height: 13px;
        width: 13px;
        margin: 1px;
        background-color: blue;
        position: relative;
    }

    .case:hover::after,
    .case:focus::after {
        content: attr(aria-label);
        background-color: black;
        color: white;
        /* on affiche aria-label */
        position: absolute;
        top: -2.4em;
        left: 50%;
        transform: translateX(-50%);
        /* on centre horizontalement  */
        z-index: 1;
        /* pour s'afficher au dessus des éléments en position relative */
        white-space: nowrap;
        /* on interdit le retour à la ligne */
    }
</style>

<body>


    <div class="calendar">


        <?php

        $date = new DateTime();

        for ($i = 0; $i < 365; $i++) {

            echo "<div class=\"case\" aria-label=\"" . ($date->modify('-1 day'))->format("d-m-Y") . "\" ></div>";
        }
        ?>
    </div>
</body>