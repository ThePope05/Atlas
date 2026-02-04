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

To auto generate Models, Views, Controllers, or Components use:
```cmd
php Atlas create [name]
```

In combination with one or multiple of the following:
```cmd
-m -v -c -comp
```
The -v and -comp commands can contains "/" to automatically make a folder for the view or component.
This doesn't work for controllers and models.

```cmd
php Atlas Route:list
```

current version 0.2
