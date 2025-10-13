const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');
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
            } else if (['.js','.jsx'].includes(path.extname(filePath))) {
                jsFilesArray.push(normalizedPath);
            }
        }
    });
}
/*
* Webpack config
*/
const config = {
	resolve: {
		extensions: [
			'.jsx',
			'.js',
			'.json',
		],
		modules: ["resources/js", "node_modules"],
		alias: {
			"@app": path.resolve(__dirname, 'resources/js/'),
		},
	},
};
mix.webpackConfig(config);

//Call the functions to get input js
const sourceFolderPath = 'resources/js';
mix.setPublicPath('public');
deleteDirectoryRecursive('public/components')
const foldersArray = [];
const jsFilesArray = [];
listFoldersAndJsFiles(sourceFolderPath, foldersArray, jsFilesArray);

/*
* Server all the file to mix
*/
for (let index = 0; index < jsFilesArray.length; index++) {
	const element = jsFilesArray[index];
	mix.js(element, element);
}
mix.postCss("resources/css/app.css", "public/components", [])
