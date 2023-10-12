<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Atenea</title>
    <link rel="stylesheet" href="assets/styles/style.css" type="text/css">
    <script type="text/javascript" src="assets/scripts/jquery.min.js"></script>
</head>
<body>
    <div  class="container">
        <h1>Atenea api documentation.</h1>
        <section class="container-model">
            <h3 class="switch-deploy">Auth - Login</h3>
            <div class="target-deploy" id="login">
                <code>
                    <span class="code-title">POST /auth</span>
                    <br>
                    <span class="code-description">/*This method is to generate the token for each user.*/</span>
                    <br>
                    <span class="code-object"></span>
                    {
                        <br>
                        &nbsp;&nbsp;&nbsp;"usuario":string,  -> Required
                        <br>
                        &nbsp;&nbsp;&nbsp;"password":string -> Required
                        <br>
                    }
                </code>
            </div>
        </section>

        <section class="container-model">
            <h3 class="switch-deploy">Question</h3>
            <div class="target-deploy" id="question">
                <code>
                    <span class="code-title">GET /question</span>
                    <br>
                    <span class="code-description">/*This method is to get the question with its respective answers.*/</span>
                    <br>
                    headers:
                    <br>
                    &nbsp;&nbsp;&nbsp;id:int, -> Required
                    <br>
                    &nbsp;&nbsp;&nbsp;token:string, -> Required
                </code>
            </div>
        </section>

        <section class="container-model">
            <h3 class="switch-deploy">Match</h3>
            <div class="target-deploy" id="match">
                <code>
                    <span class="code-title">GET /match</span>
                    <br>
                    <span class="code-description">/*This method is to get the created game.*/</span>
                    <br>
                    headers:
                    <br>
                    &nbsp;&nbsp;&nbsp;token:string, -> Required
                </code>
                <code>
                    <span class="code-title">POST /match</span>
                    <br>
                    <span class="code-description">/*This method is to create default match.*/</span>
                    <br>
                    <span class="code-object"></span>
                    {
                        <br>
                        &nbsp;&nbsp;&nbsp;"token":string, -> Required
                        <br>
                    }
                    <br>
                    <span class="code-title">POST /match</span>
                    <br>
                    <span class="code-description">/*This method consists of creating a personalized game of a certain number of questions at a certain level.*/</span>
                    <br>
                    <span class="code-object"></span>
                    {
                        <br>
                        &nbsp;&nbsp;&nbsp;"token":string, -> Required
                        <br>
                        &nbsp;&nbsp;&nbsp;"quantity":int, -> Required
                        <br>
                        &nbsp;&nbsp;&nbsp;"level":int, -> Required
                        <br>
                    }
                </code>
            </div>
        </section>
    </div>
    <script type="text/javascript" src="assets/scripts/home.js"></script>
</body>
</html>