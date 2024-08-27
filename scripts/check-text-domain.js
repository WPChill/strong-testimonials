const glob = require('glob');
const fs = require('fs');

const textDomain = 'strong-testimonials';
const keywords = [
    '__:1,2d', '_e:1,2d', '_x:1,2c,3d', 'esc_html__:1,2d', 'esc_html_e:1,2d',
    'esc_html_x:1,2c,3d', 'esc_attr__:1,2d', 'esc_attr_e:1,2d', 'esc_attr_x:1,2c,3d',
    '_ex:1,2c,3d', '_n:1,2,4d', '_nx:1,2,4c,5d', '_n_noop:1,2,3d', '_nx_noop:1,2,3c,4d'
];

const files = glob.sync('**/*.php', { ignore: ['node_modules/**', 'vendor/**', 'build/**'] });

files.forEach(file => {
    const content = fs.readFileSync(file, 'utf8');
    let hasError = false;

    keywords.forEach(keyword => {
        const regex = new RegExp(`${keyword.split(':')[0]}\\s*\\(\\s*['"](?!${textDomain})`, 'g');
        if (regex.test(content)) {
            console.error(`Error in ${file}: Incorrect text domain for ${keyword.split(':')[0]}`);
            hasError = true;
        }
    });

    if (!hasError) {
        console.log(`${file}: OK`);
    }
});