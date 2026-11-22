// tools/wp.mjs
import { spawnSync } from "node:child_process";
import { existsSync, readdirSync, readFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const tools = dirname(fileURLToPath(import.meta.url));
const cache = join(tools, ".local-paths.json");

function findLocalPhp() {
	const root = join(
		process.env.LOCALAPPDATA ?? "",
		"Programs",
		"Local",
		"resources",
		"extraResources",
		"lightning-services",
	);

	if (!existsSync(root)) {
		throw new Error(`Không tìm thấy Local tại: ${root}`);
	}

	const candidates = [];

	function walk(dir) {
		for (const entry of readdirSync(dir, { withFileTypes: true })) {
			const full = join(dir, entry.name);

			if (entry.isDirectory()) {
				walk(full);
			} else if (entry.name === "php.exe") {
				const match = full.match(/php-(\d+\.\d+\.\d+)/);
				candidates.push({
					path: full,
					ver: match ? match[1] : "0.0.0",
				});
			}
		}
	}

	walk(root);

	if (candidates.length === 0) {
		throw new Error("Không tìm thấy php.exe trong Local.");
	}

	// Ưu tiên version mới nhất
	candidates.sort((a, b) =>
		b.ver.localeCompare(a.ver, undefined, { numeric: true }),
	);
	return candidates[0].path;
}

function resolvePhp() {
	if (existsSync(cache)) {
		const { Php } = JSON.parse(readFileSync(cache, "utf8"));
		if (existsSync(Php)) return Php;
	}

	const php = findLocalPhp();
	return php;
}

const php = resolvePhp();

const result = spawnSync(
	php,
	[join(tools, "wp-cli.phar"), ...process.argv.slice(2)],
	{ stdio: "inherit" },
);

process.exit(result.status ?? 1);
