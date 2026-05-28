import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import fs from 'fs';

function deleteDirectoryRecursive(dir) {
    if (fs.existsSync(dir)) {
        fs.readdirSync(dir).forEach(file => {
            const curPath = path.join(dir, file);
            if (fs.lstatSync(curPath).isDirectory()) {
                deleteDirectoryRecursive(curPath);
            } else {
                fs.unlinkSync(curPath);
            }
        });
        fs.rmdirSync(dir);
    }
}

function getAllJsFiles(dir, fileList = []) {
    const files = fs.readdirSync(dir);
    files.forEach(file => {
        const filePath = path.join(dir, file);
        const baseName = path.basename(filePath);

        if (!['includes', 'components', 'Components', 'Includes'].includes(baseName)) {
            if (fs.statSync(filePath).isDirectory()) {
                getAllJsFiles(filePath, fileList);
            } else if (['.js', '.jsx'].includes(path.extname(file))) {
                fileList.push(filePath);
            }
        }
    });
    return fileList;
}

// Clear previous build
deleteDirectoryRecursive('public/resources');

const jsFiles = getAllJsFiles('resources/js');

export default defineConfig({
    plugins: [
        laravel({
            input: [...jsFiles, 'resources/css/app.css'],
            refresh: true,
        }),
        react({
            runtime: 'automatic'   // Best for React 19
        }),
        tailwindcss(),
    ],

    resolve: {
        alias: {
            '@app': path.resolve(__dirname, 'resources/js'),
        },
    },

    build: {
        outDir: 'public',
        emptyOutDir: false,
        rollupOptions: {
            input: jsFiles,
            output: {
                entryFileNames: (chunk) => {
                    const relative = path.relative('resources/js', chunk.facadeModuleId || '');
                    return `resources/js/${relative.replace(/\.jsx?$/, '.js')}`;
                },
                chunkFileNames: 'resources/js/[name]-[hash].js',
                assetFileNames: 'resources/css/[name][extname]',
            },
        },
    },
});