import { dirname, resolve } from "node:path";
import { fileURLToPath } from "node:url";
import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";

// Fix bắt buộc cho ESM: tự định nghĩa __dirname
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const SITE = "http://flocpress.code.media/";

export default defineConfig({
	plugins: [
		liveReload(["**/*.php", "!vendor/**", "!node_modules/**", "!dist/**"]),
	],
	server: {
		host: "127.0.0.1",
		port: 5173,
		strictPort: true,
		origin: "http://127.0.0.1:5173",
		cors: { origin: [SITE, "http://localhost:5173"] },
		allowedHosts: ["flocpress.local", "127.0.0.1"],
	},
	build: {
		outDir: "dist",
		emptyOutDir: true,
		manifest: true,
		target: "es2020",
		rollupOptions: {
			input: {
				main: resolve(__dirname, "resources/scripts/main.js"),
				editor: resolve(__dirname, "resources/scripts/editor.js"),
				theme: resolve(__dirname, "resources/styles/main.scss"),
				edit: resolve(__dirname, "resources/styles/editor.scss"),
			},
			output: {
				entryFileNames: "js/[name].[hash].js",
				chunkFileNames: "js/[name].[hash].js",
				assetFileNames: (info) => {
					const name = info.name || "";
					return name.endsWith(".css")
						? "css/[name].[hash][extname]"
						: "assets/[name].[hash][extname]";
				},
			},
		},
	},
	css: {
		devSourcemap: true,
		preprocessorOptions: {
			scss: {
				loadPaths: [resolve(__dirname, "resources/styles")],
			},
		},
	},
});
