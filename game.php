<?php
const DIV_NUMBER = 9;
?>

<head>
    <title>Tic tac toe</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="overlay hidden">
        <div class="modal">
            <div class="modal__title">
                <p></p>
                <p></p>
            </div>
            <div class="modal__button_container">
                <div class="modal__button" id="newGame">New game</div>
                <div class="modal__button"><a href="index.php" class="modal__button_text">Main page</a></div>
            </div>
        </div>
    </div>

    <div class="game__container">
        <?php
        // Generate main HTML page
        for($i = 0; $i < DIV_NUMBER; $i++) {
            echo "<div class=\"game__cell\" id=\"position_$i\"></div>\n";
        }
        ?>
    </div>

</body>

<script>
    let userId = 1;
    let firstUserCombination = [];
    let secondUserCombination = [];
    let data = {};

    function clickCell(event) {
        if(event.target.className === "game__cell") {
            if(userId % 2 !== 0) {
                firstUserCombination.push(Number(event.target.id.replace('position_', '')));
                data = {
                    "currentUser": userId,
                    "clickedCell": event.target.id,
                    "userCombination": firstUserCombination,
                };
            } else if (userId % 2 === 0) {
                secondUserCombination.push(Number(event.target.id.replace('position_', '')));
                data = {
                    "currentUser": userId,
                    "clickedCell": event.target.id,
                    "userCombination": secondUserCombination,
                };
            }

            jQuery.ajax({
                method: "POST",
                url: "server.php",
                data: data,
                success: function(response){
                    console.log(response)
                    // Parse response
                    response = JSON.parse(response)

                    // Draw either cross or circle
                    if(userId % 2 === 1) {
                        document.getElementById(response['clickedCell']).classList.add("game__cell-clicked_user1")
                    } else if (userId % 2 === 0) {
                        document.getElementById(response['clickedCell']).classList.add("game__cell-clicked_user2")
                    }

                    // Check whether game is finished
                    if(response['gameFinished']) {
                        if(response['winner']) {
                            response['combination'].forEach(elem => {
                                document.getElementById(('position_' + elem)).style.background = "#04A777"
                            })
                            modalSwitch()
                            document.querySelector('.modal__title').firstElementChild.textContent = 'Congratulations!'
                            document.querySelector('.modal__title').lastElementChild.textContent = `User ${response['winner']} win!`

                        } else {
                            modalSwitch()
                            document.querySelector('.modal__title').firstElementChild.textContent = 'Draw!'
                            document.querySelector('.modal__title').lastElementChild.textContent = 'You both are winner!'
                        }
                        gameSpace.removeEventListener("click", clickCell)
                        return

                    }
                    // Define next user
                    userId = response['nextUserId']
                }
            })
        }

    }

    let gameSpace = document.querySelector(".game__container")
    gameSpace.addEventListener("click", clickCell)

    function modalSwitch() {
        document.querySelector('.overlay').classList.toggle('hidden')
    }
    document.querySelector('#newGame').addEventListener("click", function () {
        modalSwitch();
        location.reload();
    })

</script>

