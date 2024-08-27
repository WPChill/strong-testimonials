const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

const pluginSlug = 'strong-testimonials';
const version = require('../package.json').version;

const output = fs.createWriteStream(path.join(__dirname, `../${pluginSlug}-${version}.zip`));
const archive = archiver('zip', {
    zlib: { level: 9 }
});

output.on('close', function () {
    console.log(archive.pointer() + ' total bytes');
    console.log('Archive has been finalized and the output file descriptor has closed.');
});
archive.on('error', function (err) {
    throw err;
});

archive.pipe(output);

archive.directory('build/admin/', `${pluginSlug}/admin`);
archive.directory('build/includes/', `${pluginSlug}/includes`);
archive.directory('build/assets/', `${pluginSlug}/assets`);
archive.directory('build/languages/', `${pluginSlug}/languages`);
archive.directory('build/public/', `${pluginSlug}/public`);
archive.directory('build/templates/', `${pluginSlug}/templates`);
archive.file('build/strong-testimonials.php', { name: `${pluginSlug}/strong-testimonials.php` });
archive.file('build/readme.txt', { name: `${pluginSlug}/readme.txt` });
archive.file('build/changelog.txt', { name: `${pluginSlug}/changelog.txt` });

archive.finalize();