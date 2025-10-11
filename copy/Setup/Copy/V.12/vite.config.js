import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import fs from 'fs';

//vpx_imports

/*
* Delete build files
* @param string directoryPath
*/
function deleteDirectoryRecursive(directoryPath) {
    if (fs.existsSync(directoryPath)) {
        fs.readdirSync(directoryPath).forEach((file) => {
            const filePath = path.join(directoryPath, file);
            if (fs.lstatSync(filePath).isDirectory()) {
                deleteDirectoryRecursive(filePath);
            } else {
                fs.unlinkSync(filePath);
            }
        });
        fs.rmdirSync(directoryPath);
        console.log('Directory deleted:', directoryPath);
    }
}

/*
* Get all the js files
* @param string folderPath
* @param array foldersArray
* @param array jsFilesArray
*/
function listFoldersAndJsFiles(folderPath, foldersArray, jsFilesArray) {
    const files = fs.readdirSync(folderPath);
    files.forEach(file => {
        const filePath = path.join(folderPath, file);
        const normalizedPath = path.normalize(filePath).replace(/\\/g, '/');
        const stats = fs.statSync(filePath);
        const lastFolder = path.basename(normalizedPath);
        if (!['includes', 'components', 'Components', 'Includes'].includes(lastFolder)) {
            if (stats.isDirectory()) {
                foldersArray.push(normalizedPath);
                listFoldersAndJsFiles(filePath, foldersArray, jsFilesArray);
            } else if (['.js', '.jsx'].includes(path.extname(filePath))) {
                jsFilesArray.push(normalizedPath);
            }
        }
    });
}

// Get all JS files in the 'resources/js' directory (similar to how Mix does it)
const sourceFolderPath = 'resources/js';
deleteDirectoryRecursive('public/components');
const jsFilesArray = [];
listFoldersAndJsFiles(sourceFolderPath, [], jsFilesArray);


/*
* Vite config
*/
export default defineConfig({
    plugins: [
        // Use Laravel Vite plugin (similar to how Mix handles assets)
        laravel({
            input: [...jsFilesArray, 'resources/css/app.css'], // Input all JS files dynamically like Mix does
            refresh: true, // This makes sure the assets get refreshed during development
        }),
        tailwindcss()
    ],
    resolve: {
        // Set up aliases to simplify imports (similar to Laravel Mix)
        alias: {
            '@app': path.resolve(__dirname, 'resources/js'),
        },
        extensions: ['.js', '.jsx'], // Support for .js and .jsx file extensions
    },
    build: {
        outDir: 'public/components', // Define the output directory (similar to Mix's public directory)
        rollupOptions: {
            output: {
                // Define how the output files are named (mimicking Mix's file naming convention)
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name][extname]',
            },
        },
        manifest: false, // Disable the manifest file for better performance
    },
});
