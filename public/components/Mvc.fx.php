<h2>MVC</h2>
<article>
    <h4>Controllers</h4>
    <p>
        Controllers are used to combine the data gotten by models,
        and the views the user requests.
        In the controller we can also validate, prep and alter data.
        Controllers are in charge while models only get the data,
        views only specify how a page should be built,
        and routes only define what is accesible to the user.
        <br>
        <br>
        Controllers have some pre built logic, like a model property.
        Which points to the accompanied model,
        IF the model and controller were both built by Atlas in one command.
        This will automatically pre define the connection to the correct model.
        <br>
        <br>
        Accessing a method within this model would look like this:
    </p>

    <span class="snippet gradient-box">
        $this->model->GetUser($id);
        <a onclick="copyText('$this->model->GetUser(PARAMETERS);')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        To access views or redirect the user, use:
    </p>

    <span class="snippet gradient-box">
        $this->view("index");
        <a onclick="copyText('$this->view(\'VIEWNAME\');')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <span class="snippet gradient-box">
        $this->redirect("url");
        <a onclick="copyText('$this->redirect(\'URL\');')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        You can also pass data to the view, by giving it a second parameter.
        This must then be an associative array.
    </p>

    <span class="snippet gradient-box">
        $this->view("products/index", ["allProducts" => $allProducts]);
        <a onclick="copyText('$this->view(\'VIEWNAME\', [\'ARRAYKEY\' => VALUES])')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        And then to access this in the view use:
    </p>

    <span class="snippet gradient-box">
        $data["allProducts"];
        <a onclick="copyText('$data[\'ARRAYKEY\']')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h4>Models</h4>
    <p>
        Models are a little more complex than the controllers,
        the models contain a querrybuilder.
        Meaning you can get, update, insert and delete data,
        without having to write raw sql.
        This also means that if you don't know sql this is going to be tough.
        <br>
        <br>
        Let's start with the basics.
    </p>

    <h5>Getting data</h5>

    <p>
        If we want to get everything from a table we can use:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        When we don't select any fields, the querybuilder will automatically get "*".
        To select specific fields, we can use:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->select(['id','name','description']) <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->select([\'FIELDNAME\'])->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        Let's add a where statement to this!
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->select(['id','name','description']) <br>
        ->where('id', '=', $id) <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->select([\'FIELDNAME\'])->where(\'FIELDNAME\', \'=\', VALUE)->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        Alright now let's add a join on a categories table.
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->where('products.id', '=', $id) <br>
        ->join('categories', 'products.category_id', '=', 'categories.id') <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'LEFTTABLE\')->where(\'FIELDNAME\', \'=\', \'VALUE\')->join(\'RIGHTTABLE\', \'RIGHTTABLE.LEFT_ID\', \'=\', \'LEFTTABLE.ID\')->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        The default join is an INNER join, but we can easily change this to any existing join.
        By adding it as a string to the join function call.
        Like so:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->where('products.id', '=', $id) <br>
        ->join('categories', 'products.category_id', '=', 'categories.id', 'LEFT') <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'LEFTTABLE\')->where(\'FIELDNAME\', \'=\', VALUE)->join(\'RIGHTTABLE\', \'RIGHTTABLE.LEFT_ID\', \'=\', \'LEFTTABLE.ID\', \'LEFT\')->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        "But now all my data is unordered!!" I hear you say.
        Excelent point, let's do something about it.
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->orderBy('price') <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->orderBy(\'FIELDNAME\')->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        Say that you only want a limited amount of records back,
        or even just the first one.
        We can either limit the amount returned
        OR we can call "first" instead of "get".
        Which means these 2 lines do effectively the same thing.
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->limit(1) <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->limit(AMOUNT)->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->first();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->first();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        Combined with the offset, we can easily make a paginator.
        Splitting records into groups, within the database.
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->limit(10) <br>
        ->offset(10) <br>
        ->get();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->limit(AMOUNT)->offset(AMOUNT)->get();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        To count records, instead of actually getting them.
        We can easily use:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('products') <br>
        ->count();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->count();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h5>Inserting data</h5>

    <p>
        To insert a record into a table, we can once again use an associative array.
        If we would insert a new user into the users table, we would do:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('users') <br>
        ->insert([ 'name' => 'John', 'surname' => 'Doo']);
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->insert([ \'FIELDNAME\' => \'VALUE\']);')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <p>
        That's it! <br>
        Darn, I spelled the users name wrong, let's update it to the right values.
        Doing an update is NOT allowed without a where.
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('users') <br>
        ->where('id', '=', 1) <br>
        ->update(['name' => 'John', 'surname' => 'Doe']);
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->where(\'FIELDNAME\', \'=\', VALUE)->update([\'FIELDNAME\' => \'VALUE\']);')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h5>Deleting data</h5>

    <p>
        Deleting a record from a table, is again not allowed without a where. <br>
        To delete a record we do:
    </p>

    <span class="snippet gradient-box">
        $this->db <br>
        ->table('users') <br>
        ->where('id', '=', 1) <br>
        ->delete();
        <a onclick="copyText('$this->db->table(\'TABLENAME\')->where(\'FIELDNAME\', \'=\', VALUE)->delete();')">
            <span class="material-symbols-outlined">
                content_copy
            </span>
        </a>
    </span>

    <h4>Views & Components</h4>

    <p>
        There are some simple syntax rules for "flux" files.
        Flux files are compiled into php files, to make the writing of pages easier.
    </p>
</article>