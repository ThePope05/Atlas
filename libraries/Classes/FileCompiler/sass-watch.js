const { spawn } = require("child_process");
const fs = require("fs");
const path = require("path");

const watch = !process.argv.includes("--build");
const args = watch ? ["--watch", "public/scss:public/css"] : ["public/scss:public/css"];

const modulesDir = path.join(__dirname, "../../../modules");

if (!fs.existsSync(modulesDir)) {
    fs.mkdirSync(modulesDir);
}

fs.readdirSync(modulesDir, { withFileTypes: true })
    .filter((dir) => dir.isDirectory())
    .forEach((dir) => {
        const scss = path.join("modules", dir.name, "scss");
        const css = path.join("modules", dir.name, "css");

        if (fs.existsSync(scss)) {
            args.push(`${scss}:${css}`);
        }
    });

spawn("sass", args, { stdio: "inherit", shell: true });

// Only drive tsc when the project actually opted into TypeScript
if (fs.existsSync(path.join(__dirname, "../../../tsconfig.json"))) {
    spawn("tsc", watch ? ["--watch"] : [], { stdio: "inherit", shell: true });
}
