<h2>Setup</h2>
<article>
    <h4 id="requirments">Requirments</h4>
    <ul>
        <li>Composer</li>
        <li>Php 8.4+</li>
        <li>Node js</li>
        <li>Mysql</li>
        <li>Git</li>
    </ul>
    <h4 id="gettingstarted">Getting started</h4>
    <p>
        To get started we first have to clone the Atlas repository,
        or clone my structure tool. Which contains the latest version of Atlas.
    </p>
    <span class="snippet gradient-box">
        git clone https://github.com/ThePope05/Atlas.git
        <a onclick="copyText('git clone https://github.com/ThePope05/Atlas.git')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>
    <p>
        Or you can have a look at
        <a href="https://github.com/ThePope05/StructureTool">the structure tool</a>.
        Now that we have the project localy you can copy it and move it to where you want.
        Don't forget to remove the git folder if you wan't to use the project for your own git repo.
        <br>
        Great! now we're ready to start... right? No..
        we need to install some packages and let composer link all the classes within the project.
        <br>
        <br>
        So first we run:
    </p>
    <span class="snippet gradient-box">
        npm i
        <a onclick="copyText('npm i')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>
    <p>
        and:
    </p>
    <span class="snippet gradient-box">
        composer dumpautoload
        <a onclick="copyText('composer dumpautoload')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>
    <p>
        Now we have all the extra and third party files.
        We can go into the config folder.
        And duplicate the config.example.php, rename the new file to:
    </p>
    <span class="snippet gradient-box">
        config.php
        <a onclick="copyText('config.php')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>
    <p>
        In this file you will find the database name, username, password,
        and the adress and port used for localhosting.
        The file is already added to the .gitignore.
    </p>

    <h4 id="startingdevelopment">Starting development</h4>
    <p>
        Within the framework we have an Atlas script to help us with development.
        We can use this script to start our localhost.
    </p>

    <span class="snippet gradient-box">
        php Atlas localhost
        <a onclick="copyText('php Atlas localhost')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        For the styling to work make sure to run:
    </p>
    <span class="snippet gradient-box">
        npm run dev
        <a onclick="copyText('npm run dev')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>
</article>