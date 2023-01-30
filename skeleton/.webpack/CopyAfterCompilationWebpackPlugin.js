const fs = require('fs');

class CopyAfterCompilationWebpackPlugin {
    constructor(files = []) {
        this.files = files;
    }

    apply(compiler) {
        compiler.hooks.done.tap('CopyAfterCompilationWebpackPlugin', () => {
            this.files
                .filter(file => (
                    (fs.existsSync(file.source) && fs.existsSync(file.destination) && !fs.readFileSync(file.source).equals(fs.readFileSync(file.destination)))
                    ||
                    (fs.existsSync(file.source) && !fs.existsSync(file.destination))
                ))
                .forEach((file) => {
                    console.log('Copying:', file.source, file.destination)
                    fs.copyFileSync(file.source, file.destination);
                    console.log(`Copied: '${file.source}' > '${file.destination}'`);
                });
        });
    }
}

module.exports = CopyAfterCompilationWebpackPlugin;