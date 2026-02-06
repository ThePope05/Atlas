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

The -v and -comp commands can contains "/" to automatically make a folder for the view or component.
This doesn't work for controllers and models.

- -m = Model
- -v = View
- -c = Controller
- -d = Database file
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

To run all database files in app/db/ use:

```cmd
php Atlas db
```

To run specific files add the number of the file to the end of the command.
Atlas will keep track of the files that have been run. To rerun all files run:

```cmd
php Atlas db:refresh
```

And to force run a file use (the file number is optional, including no file number will force run all files):

```cmd
php Atlas db:force [file number]
```

<br>
<br>
<br>
<br>
<br>

current version 0.2
