<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu App - Giriş</title>
    <link rel="stylesheet" href="styles/entrance.css">
    <link rel="stylesheet" href="styles/button.css">
</head>

<body>
    <div class="entrance-container">
        <button class="back-btn" style="display: none">
            <img src="images/backButton.png" alt="back-button">
        </button>

        <h2>Welcome!</h2>

        <!-- chooseIdenty -->
        <div class="chooseIdenty">
            <button id="student-btn">Student</button>
            <button id="personnel-btn">Personnel</button>
        </div>

        <!-- student logIn -->
        <div id="student-login" style="display: none">
            <label for="StudentNo">Student ID:</label>
            <input type="text" id="StudentNo" placeholder="XXXXXXXXXX" inputmode="numeric" autocomplete="off">

            <label for="StudentPassword">Password:</label>
            <input type="password" id="StudentPassword" placeholder="Password" autocomplete="new-password">

            <button class="logIn-btn">Log In</button>
            <button class="student-register-btn">I don't have an account</button>
        </div>

        <!-- personnel logIn -->
        <div id="personnel-login" style="display: none">
            <label for="RegistrationNum">Personnel ID:</label>
            <input type="text" id="RegistrationNum" placeholder="XXXX" inputmode="numeric" autocomplete="off">

            <label for="PersonnelPassword">Password:</label>
            <input type="password" id="PersonnelPassword" placeholder="Password" autocomplete="new-password">

            <button class="logIn-btn">Log In</button>
            <button class="personnel-register-btn">I don't have an account</button>
        </div>

        <!-- student register -->
        <div id="student-register" style="display: none">
            <label for="RegStudentNo">Student ID:</label>
            <input type="text" id="RegStudentNo" placeholder="XXXXXXXXXX" inputmode="numeric" autocomplete="off">

            <label for="student-Email">Student Email:</label>
            <input type="email" id="student-Email" placeholder="xxxxxxxxxx@ogrenci.karabuk.edu.tr" autocomplete="off">

            <label for="RegStudentPassword">Create a Password:</label>
            <input type="password" id="RegStudentPassword" placeholder="Password" autocomplete="new-password">
            <p>Password must include:
            <ul>
                <li>6 characters</li>
                <li>one uppercase</li>
                <li>one lowercase</li>
                <li>one number</li>
                <li>one special character (@$!%*?&.)</li>
            </ul>
            </p>

            <button class="signUp-btn">Sign Up</button>
        </div>

        <!-- personnel register -->
        <div id="personnel-register" style="display: none">
            <label for="personnel-Email">Personnel Email:</label>
            <input type="email" id="personnel-Email" placeholder="xxxxxxxxxx@karabuk.edu.tr" autocomplete="off">

            <label for="RegPersonnelPassword">Create a Password:</label>
            <input type="password" id="RegPersonnelPassword" placeholder="Password" autocomplete="new-password">
            <p>Password must include:
            <ul>
                <li>6 characters</li>
                <li>one uppercase</li>
                <li>one lowercase</li>
                <li>one number</li>
                <li>one special character (@$!%*?&.)</li>
            </ul>
            </p>

            <button class="signUp-btn">Sign Up</button>
        </div>
    </div>

    <script src="js/entrance/entrance-pageControl.js"></script>
    <script src="js/entrance/entrance-logInControl.js"></script>
    <script src="js/entrance/entrance-signUpControl.js"></script>
</body>

</html>