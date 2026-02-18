<!DOCTYPE html>
<html lang="en">

@component('Head')

<body>
    <div class="toast">
        <p>Copied!</p>
    </div>

    <div class="part welcome" id="welcome">
        @component('WelcomeTitle')
    </div>

    <div class="part">
        <div class="tiles">
            <a class="tile gradient-box" href="#setup">
                <h3>Setup</h3>
            </a>
            <a class="tile gradient-box" href="#commands">
                <h3>Commands</h3>
            </a>
            <a class="tile gradient-box" href="#mvc">
                <h3>MVC</h3>
            </a>
            <a class="tile gradient-box" href="#routing">
                <h3>Routing</h3>
            </a>
            <a class="tile gradient-box" href="#schemas">
                <h3>Schemas</h3>
            </a>
            <a class="tile gradient-box" href="#modules">
                <h3>Modules</h3>
            </a>
        </div>
    </div>

    <nav class="nav gradient-box">
        <a href="#welcome">Top</a>
        <ul>
            <li>
                <a href="#setup">Setup</a>
                <ul>
                    <li><a href="#requirments">Requirments</a></li>
                    <li><a href="#gettingstarted">Getting started</a></li>
                    <li><a href="#startingdevelopment">Starting development</a></li>
                </ul>
            </li>
            <li>
                <a href="#commands">Commands</a>
                <ul>
                    <li>
                        <a href="#atlas">Atlas</a>
                        <ul>
                            <li><a href="#createcommands">Create commands</a></li>
                            <li><a href="#databasecommands">Database commands</a></li>
                            <li><a href="#othercommands">Other commands</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a href="#mvc">MVC</a></li>
            <li><a href="#routing">Routing</a></li>
            <li><a href="#schemas">Schemas</a></li>
            <li><a href="#modules">Modules</a></li>
        </ul>
    </nav>

    <div class="part" id="setup">
        @component('Setup')
    </div>

    <div class="part" id="commands">
        @component('Commands')
    </div>

    <div class="part" id="mvc">
        @component('Mvc')
    </div>

    <div class="part" id="routing">
        @component('Routing')
    </div>

    <div class="part" id="schemas">
        @component('Schemas')
    </div>

    <div class="part" id="modules">
        @component('Modules')
    </div>

    <script src="/public/js/app.js"></script>
</body>

</html>