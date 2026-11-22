// lint-staged.config.js — chạy PHP qua container, không cài PHP vào máy
import path from "node:path";

const rel = (files) =>
	files.map((f) => path.relative(process.cwd(), f)).join(" ");

export default {
	"resources/styles/**/*.scss": (f) => `npx stylelint --fix ${rel(f)}`,
	"resources/scripts/**/*.js": (f) => `npx eslint --fix ${rel(f)}`,
	"app/**/*.php": (f) =>
		`docker compose run --rm -T composer php vendor/bin/php-cs-fixer fix ${rel(f)}`,
};
