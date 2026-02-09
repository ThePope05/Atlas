# 🌐 Atlas

Atlas is a modular back end framework, which I made for a specific project.

## 🔧 Setup

To use Atlas simply clone the repository, and run:

```cmd
composer dumpautoload
```

```cmd
npm install
```

Or clone [my structure tool](https://github.com/ThePope05/StructureTool), which also contains the Atlas framework.
And then run (you still need to run the commands above):

```cmd
make Atlas 'your_project_name'
```

## 🏃 Running the project

To local host run:

```cmd
php Atlas localhost
```

```cmd
npm run dev
```

## 💻 Atlas commands for DEV

```cmd
php Atlas localhost
```

To auto generate Models, Views, Controllers, Database files, or Components use:

```cmd
php Atlas create [name]
```

In combination with one or multiple of the following:

```cmd
-m -v -c -d -comp -mod
```

The -v and -comp commands can contain "/" to automatically make a folder for the view or component.
This doesn't work for controllers and models.

- -m = Model
- -v = View
- -c = Controller
- -d = Database schema
- -comp = Component
- -mod = Module
  <hr>

```cmd
php Atlas Route:list
```

To clear all compiled flux files run:

```cmd
php Atlas Cache:clear
```

To run all database schemas in app/db/ use:

```cmd
php Atlas db
```

To force wipe and rerun all schemas run:

```cmd
php Atlas db:refresh
```

<br>
<br>
<br>
<br>
<br>

current version 0.5
