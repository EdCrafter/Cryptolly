<?php
session_start();
?>
<style>
    @import url(https://fonts.googleapis.com/css?family=Lunasima:regular,700);
</style>
<div class="navbarr">
    <button onclick="window.location.href = 'index.php';">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40">
            <path d="M28.658 38.0288C25.6004 39.4972 22.2195 40.1638 18.8334 39.9659C15.4472 39.7681 12.167 38.7123 9.30118 36.8978C6.43537 35.0833 4.07812 32.5698 2.45109 29.5935C0.824058 26.6173 -0.0192945 23.2761 0.000334905 19.8843C0.0199643 16.4924 0.90193 13.1612 2.5633 10.204C4.22467 7.2468 6.61085 4.76071 9.49747 2.97952C12.3841 1.19832 15.6763 0.180544 19.0645 0.0218898C22.4527 -0.136764 25.8257 0.568918 28.8661 2.07258L24.5399 10.8203C22.983 10.0503 21.2559 9.689 19.521 9.77023C17.7861 9.85147 16.1003 10.3726 14.6222 11.2847C13.1441 12.1967 11.9223 13.4697 11.0716 14.984C10.2209 16.4982 9.76925 18.2039 9.7592 19.9407C9.74915 21.6775 10.181 23.3884 11.0141 24.9124C11.8472 26.4363 13.0542 27.7234 14.5217 28.6525C15.9891 29.5816 17.6687 30.1222 19.4026 30.2235C21.1365 30.3249 22.8677 29.9835 24.4333 29.2316L28.658 38.0288Z" fill="url(#paint0_linear_2501_1369)" />
            <ellipse cx="19.7622" cy="19.7617" rx="6.90476" ry="6.90476" fill="#30E0A1" />
            <defs>
                <linearGradient id="paint0_linear_2501_1369" x1="40.9333" y1="10.5085" x2="-1.42984" y2="13.7998" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#246CF9" />
                    <stop offset="0.0001" stop-color="#1E68F6" />
                    <stop offset="1" stop-color="#0047D0" />
                </linearGradient>
            </defs>
        </svg>
    </button>
    <div class="menu" id="main-menu">
        <button class="menu-toggle" id="toggle-menu">
            Collapse menu
        </button>
        <div class="menu-dropdown">
            <ul>
                <li><a>Exchange</a></li>
                <li><a>Pricing</a></li>
                <li><a>Wallet</a></li>
                <li><a>Company</a></li>
                <li><a href="members.php">Members</a></li>
            </ul>
            <?php
                if (isset($_SESSION['username'])) {
                    $user = $_SESSION['username'];
                    $loggedin = true;
                }
                else {
                    $loggedin = false;
                }
                if ($loggedin) {
                    echo <<<_LOGGEDIN
                    <div class="menu-buttons">
                        <button class="button-container--light" onclick="window.location.href = 'logout.php';">
                            Log out
                        </button>
                        <button class="button-container" onclick="window.location.href = 'profile.php';">
                            Profile
                        </button>
                        <button class="button-container" onclick="window.location.href = 'friends.php';">
                            Friends
                        </button>
                    </div>
_LOGGEDIN;
                }
                else {
                    echo <<<_GUEST
                    <div class="menu-buttons">
                        <button class="button-container--light" onclick="window.location.href = 'registration.php';">
                            Sign in
                        </button>
                        <button class="button-container" onclick="window.location.href = 'login.php';">
                            Get Started
                        </button>
                    </div>
_GUEST;
                }
                
                
            ?>
        </div>
    </div>
</div>
