const { spawn } = require("child_process");
const fs = require("fs");
const path = require("path");

const args = ["--watch", "public/scss:public/css"];

const modulesDir = path.join(__dirname, "../../../modules");

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
