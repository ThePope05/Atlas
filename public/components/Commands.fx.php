<h2>Commands</h2>
<article>
    <h4 id="atlas">Atlas</h4>
    <p>
        Within the framework we have an Atlas script to help us with development.
        With this script we can create models, controllers, views, schemas, components and modules.
        It can also help us debug routes, clear our cache and run schemas.
        All commands executed by Atlas must be preceded with:
    </p>

    <span class="snippet gradient-box">
        php Atlas
        <a onclick="copyText('php Atlas')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h5 id="createcommands">Create commands</h5>
    <p>
        There are many files you can create with Atlas, and many ways to mess it up.
        Though it's really quite easy.
        All create commands are always preceded with:
    </p>

    <span class="snippet gradient-box">
        php Atlas create
        <a onclick="copyText('php Atlas create')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        Followed by a file name,
        and then one or multiple options.
        These options consist of:
    </p>

    <ul>
        <li>-m = model</li>
        <li>-c = controller</li>
        <li>-v = view</li>
        <li>-d = schema</li>
        <li>-a = model, controller, schema</li>
        <li>-comp = component</li>
        <li>-mod = module</li>
    </ul>

    <p>
        Controllers, models and schemas only have to contain the subject.
        Meaning if we want to create a controller for products, all we have to do is:
    </p>

    <span class="snippet gradient-box">
        php Atlas create product -c
        <a onclick="copyText('php Atlas create product -c')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        This would make a controller named "ProductController" in "app/Controllers".
        If we would have used the option "-a" instead,
        we would have gotten the same controller.
        Accompanied by a model "ProductModel", and a schema "01_create_table_products".
        <br>
        <br>
        Notice that Atlas will make your passed name plural for the table name.
        Atlas also automatically numbers your schema files,
        this is important because the numbers indicates the execution order of the schemas.
        This execution order is shared through modules,
        you are allowed to change the numbers of schema files.
        But be carefull,
        because once schema files are executed they will run again after re-numbering.
        <br>
        <br>
        When making any file (except modules) you can add "-mod=MODULENAME",
        this will make Atlas create that file for a specific module.
        If it can be found, if it can't be found it won't be created.
        Again, this doesn't work for modules.
        <br>
        <br>
        When modules are created they are automatically added to the
        modules.json file in the config folder.
        <br>
        <br>
        When making views, and components you can add part of a path to that filename.
        This can help with creating subfolders in the views and components folder.
        This would look like:
    </p>

    <span class="snippet gradient-box">
        php Atlas create products/create_form -v
        <a onclick="copyText('php Atlas create products/create_form -v')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h5 id="databasecommands">Database commands</h5>

    <h5 id="othercommands">Other commands</h5>
</article>